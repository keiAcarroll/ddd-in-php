<?php
declare(strict_types = 1);

require_once './AggregateRoot.php';

class Post extends AggregateRoot
{
    private $id;
    private $title;
    private $content;
    private $published = false;
    private $categories;

    private function __construct(PostId $id)
    {
        $this->id = $id;
    }

    public static function writeNewFrom($title, $content)
    {
        $postId = PostId::create();

        $post = new static($postId);

        // ドメインイベントの発行
        // 新しく投稿が作成されたことを伝える
        $post->recordApplyAndPublishThat(
            new PostWasCreated($postId, $title, $content)
        );
    }

    /**
     * このpublishは、イベントのpublish（出版）とは異なり、
     * ブログポストの公開状態設定をするメソッド
     * ちょっとややこしい...
     */
    public function publish()
    {
        $this->recordApplyAndPublishThat(
            new PostWasPublished($this->id)
        );
    }

    public function categorizeIn(CategoryId $categoryId)
    {
        $this->recordApplyAndPublishThat(
            new PostWasCategorized($this->id, $categoryId)
        );
    }

    public function changeContentFor($newContent)
    {
        $this->recordApplyAndPublishThat(
            new PostContentWasChanged($this->id, $newContent)
        );
    }

    public function changeTitleFor($newTitle)
    {
        $this->recordApplyAndPublishThat(
            new PostTitleWasChanged($this->id, $newTitle)
        );
    }

    /**
     * 以下はイベントの内容を反映するメソッド
     * AggregateRoot クラスから継承した recordApplyAndPublishThat() を実行すると、
     * その中で applyThat()が呼ばれ、イベントクラス名によって以下のどれかが実行される
     */
    protected function applyPostWasCreated(PostWasCreated $event)
    {
        $this->id = $event->id();
        $this->title = $event->title();
        $this->content = $event->content();
    }

    protected function applyPostWasPublished(PostWasPublished $event)
    {
        $this->published = true;
    }

    protected function applyPostWasCategorized(PostWasCategorized $event)
    {
        $this->categories->add($event->categoryId());
    }

    protected function applyPostContentWasChanged(PostContentWasChanged $event)
    {
        $this->content = $event->content();
    }

    protected function applyPostTitleWasChanged(PostTitleWasChanged $event)
    {
        $this->title = $event->title();
    }
}