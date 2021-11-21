<?php

namespace App;

interface PostRepository
{
    public function findAll(): array;

    public function findById(string $id): Post;

    public function save(Post $post): Post;

    public function deleteById(string $id): void;
}