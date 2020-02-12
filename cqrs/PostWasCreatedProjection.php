<?php

namespace Infrastructure\Projection\Elasticsearch;

use Elasticsearch\Client;
use PostWasCreated;
use Projection;

class PostWasCreatedProjection implements Projection
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * eventType()ではなくこっちを使う
     */
    public function listenTo()
    {
        return PostWasCreated::class;
    }

    /**
     * イベントの情報からデータストアに登録する処理
     * Projectorクラスの中で呼ばれることになる
     */
    public function project($event)
    {
        $this->client->index([
            'index' => 'posts',
            'type' => 'post',
            'id' => $event->getPostId(),
            'body' => [
                'content' => $event->getPostContent(),
                // ...
            ]
        ]);
    }

    /**
     * 本には書かれてないですが、
     * Projectorクラスにprojectionを格納する際の
     * 配列のキーで呼び出している eventType() メソッドは
     * おそらくこんな感じになるんじゃないかなぁ！
     * 他のイベントProjectionを実装したとしても全く同じ処理になるはずなので
     * さすがにこれは抽象クラスに持たせて継承するのが良いかもですね！！！！！
     * （ていうかたぶんlistenTo()メソッドを使うべきところを
     * 　表記ミスしてるだけの気がする〜...）
     */
    public function eventType()
    {
        return str_replace('Projection', '', get_class($this));
    }
}