<?php

/**
* MODEL LAYER
* -----------
* Represents whole collection of blog posts available.
*/
class PostRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO('sqlite:' . __DIR__ . '/database.sql');
    }

    public function add(Post $post): void
    {
        $this->db->beginTransaction();

        try {
            $stm = $this->db->prepare('INSERT INTO posts (title, content) VALUES (?, ?)');

            $stm->execute([
                $post->title(),
                $post->content(),
            ]);

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            throw new UnableToCreatePostException($e);
        }


    }
}