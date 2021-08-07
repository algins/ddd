<?php

namespace App;

class PostRepository
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
        return $_SESSION['posts'];
    }

    public function add(Post $post): void
    {
        $_SESSION['posts'][] = [
            'title' => $post->title(), 
            'content' => $post->content(),
        ];
    }
}