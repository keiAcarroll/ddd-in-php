<?php

use Infrastructure\Projection\Elasticsearch\PostWasCreatedProjection;
use Infrastructure\Projection\Elasticsearch\PostWasPublishedProjection;
use Infrastructure\Projection\Elasticsearch\PostWasCategorizedProjection;
use Infrastructure\Projection\Elasticsearch\PostContentWasChangedProjection;
use Infrastructure\Projection\Elasticsearch\PostTitleWasChangedProjection;

// AMQPブローカーと接続
$cnn = new AMQPConnection();
$cnn->connect();

// チャンネルの作成
$ch = new AMQPChannel($cnn);

// 新しいキューの宣言
$ex = new AMQPQueue($ch);
$ex->setName('events');
$ex->declare();

// イベントループの作成
$loop = React\EventLoop\Factory::create();

$serializer = JMS\Serializer\SerizalizerBuilder::create()->build();

$client = new Elasticsearch\ClientBuilder::create()->build();

$projector = new Projector();
$projector->register([
    new PostWasCreatedProjection($client),
    new PostWasPublishedProjection($client),
    new PostWasCategorizedProjection($client),
    new PostContentWasChangedProjection($client),
    new PostTitleWasChangedProjection($client),
]);

$consumer = new Gos\Component\ReactAMQP\Consumer($queue, $loop, 0,5, 10);

// Check for messages every half a second and consume up to 10 at a time.
$consumer->on(
    'consume',
    function ($envelope, $queue) use ($projector, $serializer) {
        $event = $serializer->unserialize($envelope->getBody(), 'json');
        $projector->project($event);
    }
);

$loop->run();