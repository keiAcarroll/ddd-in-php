<?php
declare(strict_types = 1);

/**
 * Adapterクラス
 * RDBと、PostRepositoryインタフェースを繋ぎます
 */
class PDOPostRepository implements PostRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function byId(PostId $id)
    {
        $sql = "SELECT * FROM posts WHERE id = ?";
        $statement = $this->db->prepare($sql);

        $statement->execute([
            $id->id(),
        ]);

        return recreateFrom($statement->fetch());
    }

    public function add(Post $post)
    {
        $sql = "SELECT * FROM posts WHERE id = ?";
        $statement = $this->db->prepare($sql);

        $statement->execute([
            $post->title(),
            $post->content(),
        ]);
    }
}