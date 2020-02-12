<?php

interface EventSourcedAggregateRoot
{
    public static function reconstitute(EventStream $events);
}