<?php

namespace App\Domain\Repositories;

use App\Domain\Post;

interface PostRepository
{
    public function findAll(): array;

    public function findById(string $id): Post;

    public function save(Post $post): void;

    public function delete(Post $post): void;
}
