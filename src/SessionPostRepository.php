<?php

namespace App;

use IllegalArgumentException;

class SessionPostRepository implements PostRepository
{
    public function __construct()
    {
        session_start();

        if (!array_key_exists('posts', $_SESSION)) {
            $_SESSION['posts'] = [];
        }
    }

    public function findAll(): array
    {
        return array_map(function ($post) {
            return new Post($post['id'], $post['title'], $post['content']);
        }, $_SESSION['posts']);
    }

    public function findById(string $id): Post
    {
        $post = $_SESSION['posts'][$id] ?? null;

        if (!$post) {
            throw new IllegalArgumentException();
        }

        return new Post($post['id'], $post['title'], $post['content']);
    }

    public function save(Post $post): Post
    {
        $_SESSION['posts'][$post->getId()] = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
        ];

        return $post;
    }

    public function deleteById(string $id): void
    {
        $post = $this->findById($id);

        unset($_SESSION['posts'][$post->getId()]);
    }
}