<?php

use Doctrine\ORM\EntityManager;

class DoctrinePostRepository implements PostRepository
{
    private $em;
    private $projector;

    public function __construct(EntityManager $em, Projector $projector)
    {
        $this->em = $em;
        $this->projector = $projector;
    }

    public function save(Post $post)
    {
        $this->em->transactional(
            function (EntityManager $em) use ($post) {
                $em->persist($post);

                foreach ($post->recordedEvents() as $event) {
                    $em->persist($event);
                }
            }
        );

        $this->projector->project($post->recordedEvents());
    }

    public function byId(PostId $id)
    {
        return $this->em->find($id);
    }
}