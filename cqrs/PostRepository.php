<?php
declare(strict_types = 1);

require_once './Post.php';

interface PostRepository
{
    public function save(Post $post);
    public function byId(PostId $id);
    // ちなみにこのPostIdは値オブジェクト
}