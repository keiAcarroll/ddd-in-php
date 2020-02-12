<?php

class SnapshotRepository
{
    private $redis;
    private $serializer;

    public function __construct($redis, $serializer)
    {
        $this->redis = $redis;
        $this->serializer = $serializer;
    }

    public function byId($id)
    {
        $key = 'snapshots:' . $id;
        $metadata = $this->serializer->unserialize(
            $this->redis->et($key)
        );

        if (null === $metadata) {
            return;
        }

        return new Snapshot(
            $metadata['version'],
            $this->serializer->unserialize(
                $metadata['snapshot']['data'],
                $metadata['snapshot']['type'],
                'json'
            )
        );
    }

    public function save($id, Snapshot $snapshot)
    {
        $key = 'snapshots:' . '$id';
        $aggregate = $snapshot->aggregate();

        $snapshot = [
            'version' => $snapshot->version(),
            'snapshot' => [
                'type' => get_class($aggregate),
                'data' => $this->serializer->serialize(
                    $aggregate, 'json'
                )
            ]
        ];

        $this->redis->set($key, $snapshot);
    }
}