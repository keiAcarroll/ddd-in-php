<?php
declare(strict_types = 1);

/**
 * Port に相当する
 * ドメインモデル（内側）と、データストア（外側）のやり取りは
 * このインタフェースを通して行われる
 */
interface PostRepository
{
    // ちなみにこの PostId は値オブジェクト
    public function byId(PostId $id);
    public function add(Post $post);
}