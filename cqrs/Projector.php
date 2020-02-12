<?php

class Projector
{
    private $projections;

    /**
     * projectionの登録
     * 配列で格納、キーはイベントのクラス名
     */
    public function register(array $projections)
    {
        foreach ($projections as $projection) {
            $this->projections[$projection->eventType()] = $projection;
        }
    }

    /**
     * 格納された配列の中に対応するprojectionが存在した場合、
     * そのprojectionにイベント処理を委譲する
     */
    public function project(array $events)
    {
        foreach ($events as $event) {
            if (isset($this->projections[get_class($event)])) {
                $this->projections[get_class($event)]
                    ->project($event);
            }
        }
    }
}