<?php

namespace App\Blog\Infrastructure\Domain\Model\Post\Session;

use App\Blog\Domain\Model\Post\Post;
use App\Blog\Domain\Model\Post\PostRepository;

class SessionPostRepository implements PostRepository
{
    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!array_key_exists('posts', $_SESSION)) {
            $_SESSION['posts'] = [];
        }
    }

    public function findAll(): array
    {
        return array_map(function ($post) {
            return Post::fromRawData($post);
        }, $_SESSION['posts']);
    }

    public function findById(string $id): ?Post
    {
        $post = $_SESSION['posts'][$id] ?? null;

        if (!$post) {
            return null;
        }

        return Post::fromRawData($post);
    }

    public function save(Post $post): void
    {
        $postId = $post->getId()->getValue();

        $_SESSION['posts'][$postId] = [
            'id' => $postId,
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'author_id' => $post->getAuthorId()->getValue(),
        ];
    }

    public function delete(Post $post): void
    {
        unset($_SESSION['posts'][$post->getId()->getValue()]);
    }
}
