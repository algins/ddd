<?php

namespace App;

class PostService
{
    public function createPost(string $title, string $content): Post
    {
        $post = Post::writeNewFrom($title, $content);
        (new PostRepository())->add($post);

        return $post;
    }

    public function findAllPosts()
    {
        return (new PostRepository())->findAll();
    }
}