<?php

namespace Infrastructure\Projection;

use Gos\Component\React\AMQPProducer as Producer;
use JMS\Serializer\Serializer;

class AsyncProjector
{
    private $producer;
    private $serizalizer;

    public function __construct(Producer $producer, Serializer $serializer)
    {
        $this->producer = $producer;
        $this->serizalizer = $serializer;
    }

    public function project(array $events)
    {
        foreach ($events as $event) {
            $this->producer->publish(
                $this->serializer->serialize(
                    $event, 'json'
                )
            );
        }
    }
}
