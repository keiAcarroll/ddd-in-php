<?php

class EventStore
{
    private $redis;
    private $serializer;

    public function __construct($redis, $serializer)
    {
        $this->redis = $redis;
        $this->serializer = $serializer;
    }

    public function append(EventStream $eventStream)
    {
        foreach($eventStream as $event) {
            $data = $this->serializer->serilize(
                $event, 'json'
            );

            $date = (new DateTimeImmutable())->format('YmdHis');

            $this->redis->rpush(
                'events:' . $event->getAggregateId(),
                $this->serializer->serialize([
                    'type' => get_class($event),
                    'created_at' => $date,
                    'data' => $data
                ], 'json')
            );
        }
    }

    public function getEventsFrom($id)
    {
        $serializedEvents = $this->redis->lrange('events:' . $id, 0, -1);

        $eventStream = [];
        foreach($serializedEvents as $serializedEvent) {
            $eventData = $this->serializer->deserialize(
                $serializedEvent,
                'array',
                'json'
            );

            $eventStream[] = $this->serializer->deserialize(
                $eventData['data'],
                $eventData['type'],
                'json'
            );
        }

        return new EventStream($id, $eventStream);
    }
}