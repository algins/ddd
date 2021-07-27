<?php

/**
* MODEL LAYER
* -----------
* Orchestrates and organizes the Domain behavior.
* Direct client of Domain Model.
*/
class PostService
{
    public function createPost(string $title, string $content): Post
    {
        $post = Post::writeNewFrom($title, $content);
        (new PostRepository())->add($post);

        return $post;
    }
}