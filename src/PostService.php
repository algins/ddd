<?php

namespace App;

/**
* Orchestrate and organize the Domain behavior.
*/
class PostService
{
    private $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllPosts()
    {
        return $this->repository->all();
    }

    public function createPost($title, $body)
    {
        $post = Post::writeNewFrom($title, $body);
        $this->repository->add($post);

        return $post;
    }
}