<?php

class Post extends AggregateRoot implements EventSourcedAggregateRoot
{
    public static function reconstitute(EventStream $events)
    {
        $post = new static($history->getAggregateId());

        /**
         * 一連のイベントをすべて反映させる処理
         */
        foreach($events as $event){
            $post->applyThat($event);
        }

        return $post;
    }
}