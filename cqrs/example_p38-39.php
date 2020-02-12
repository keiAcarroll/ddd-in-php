<?php

/**
 * 用語説明
 * 
 * ブローカー ... メッセージのやり取りを仲介するプログラム
 * プロデューサー ... メッセージを発信するプログラム
 * コンシューマー ... メッセージを受け取るプログラム
 * 
 */

// AMQPブローカーと接続
$cnn = new AMQPConnection();
$cnn->connect();

// チャンネルの作成
$ch = new AMQPChannel($cnn);

// 新しい交換？の宣言
$ex = new AMQPExchange($ch);
$ex->setName('events');

$ex->declare();

// イベントループの作成
$loop = ReactEventLoopFactory::create();

// 0.5秒ごとに待機中メッセージを送信するプロデューサーの作成
$producer = new Gos\Component\React\AMQPProducer($ex, $loop, 0,5);

$serializer = JMS\Serializer\SerizalizerBuilder::create()->build();

$projector = new AsyncProjector($producer, $serizalizer);

$events = [
    new PostWasCreated(/* ... */),
    new PostWasPublished(/* ... */),
    new PostWasCategorized(/* ... */),
    new PostContentWasChanged(/* ... */),
    new PostTitleWasChanged(/* ... */),
];

$projector->project($events);