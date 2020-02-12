<?php

/**
 * Snapshotを用いた場合
 */
class EventStorePostRepository implements PostRepository
{
    private $eventStore;
    private $projector;

    public function __construct($eventStore, $projector)
    {
        $this->eventStore = $eventStore;
        $this->projector = $projector;
    }

    public function save(Post $post)
    {
        $id = $post->id();
        $events = $post->recordedEvents();
        $post->clearEvents();
        $this->eventStore->append(new EventStream($id, $events));
        $countOfEvents = $this->eventStore->countEventsFrom($id);
        $version = $countOfEvents / 100;

        if (! $this->snapshotRepository->has($post->id(), $version)) {
            $this->snapshotRepository->save(
                $id,
                new Snapshot(
                    $post, $version
                )
            );
        }

        $this->projector->project($events);
    }

    public function byId(PostId $id)
    {
        $snapshot = $this->snapshotRepository->byId($id);

        if (null === $snapshot) {
            return Post::reconstitute(
                $this->eventStore->getEventsFrom($id)
            );
        }

        $post = $snapshot->aggregate();

        $post->replay(
            $this->eventStore->fromVersion($id, $snapshot->version())
        );

        return $post;
    }
}