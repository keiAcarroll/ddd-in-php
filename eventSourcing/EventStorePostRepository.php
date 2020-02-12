<?php

/**
 * Adapterパターン
 * EventStoreクラスと、PostRepositoryインタフェースを繋ぎます
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
        $events = $post->recordedEvents();

        $this->eventStore->append(new EventStream(
            $post->id(),
            $events
        ));

        $post->clearEvents();

        $this->projector->project($events);
    }

    public function byId(PostId $id)
    {
        return Post::reconstitute(
            $this->eventStore->getEventsFrom($id)
        );
    }
}