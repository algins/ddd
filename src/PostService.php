<?php

namespace App;

class PostService
{
    private PostRepository $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findById(string $id): Post
    {
        return $this->repository->findById($id);
    }

    public function save(Post $post): Post
    {
        return $this->repository->save($post);
    }

    public function deleteById(string $id): void
    {
        $this->repository->deleteById($id);
    }
}