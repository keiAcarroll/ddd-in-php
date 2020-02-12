<?php

interface Projection
{
    public function listenTo();
    public function project($event);
}