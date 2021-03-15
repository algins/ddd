<?php

namespace App;

/**
* Represent the whole collection of blog posts available. 
*/
class PostRepository
{
    public function __construct()
    {
        session_start();

        if (!array_key_exists('posts', $_SESSION)) {
            $_SESSION['posts'] = [];
        }
    }

    public function all()
    {
        return array_values($_SESSION['posts']);
    }

    public function add(Post $post)
    {
        $postId = uniqid();

        $_SESSION['posts'][$postId] = [
            'id' => $postId,
            'title' => $post->getTitle(),
            'body' => $post->getBody(),
        ];
    }
}