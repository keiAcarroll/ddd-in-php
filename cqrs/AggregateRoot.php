<?php
declare(strict_types = 1);

/**
 * 集約ルートクラス
 */
class AggregateRoot
{
    private $recordedEvents = [];

    /**
     * イベントの処理
     * イベントを格納、内容の反映、出版を行う
     */
    protected function recordApplyAndPublishThat(DomainEvent $domainEvent)
    {
        $this->recordThat($domainEvent);
        $this->applyThat($domainEvent);
        $this->publishThat($domainEvent);
    }

    /**
     * イベントをプロパティに格納
     */
    protected function recordThat(DomainEvent $domainEvent)
    {
        $this->recordedEvents[] = $domainEvent;
    }

    /**
     * イベントの内容を反映
     * （子クラスの apply~~() メソッドに処理を移す。
     * 　一種の TemplateMethodパターン とも言えるかも）
     */
    protected function applyThat(DomainEvent $domainEvent)
    {
        $modifier = 'apply' . get_class($domainEvent);

        $this->$modifier($domainEvent);
    }

    /**
     * ドメインイベントを出版（Publish）
     */
    protected function publishThat(DomainEvent $domainEvent)
    {
        DomainEventPublisher::getInstance()->publish($domainEvent);
    }

    public function recordedEvents()
    {
        return $this->recordedEvents;
    }

    public function clearEvents()
    {
        $this->recordedEvents = [];
    }
}