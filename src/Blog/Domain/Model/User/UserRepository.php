<?php

namespace App\Blog\Domain\Model\User;

interface UserRepository
{
    public function findAll(): array;

    public function findById(string $id): ?User;

    public function save(User $user): void;

    public function delete(User $user): void;
}
