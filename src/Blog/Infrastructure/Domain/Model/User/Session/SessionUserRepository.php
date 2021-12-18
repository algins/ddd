<?php

namespace App\Blog\Infrastructure\Domain\Model\User\Session;

use App\Blog\Domain\Model\User\User;
use App\Blog\Domain\Model\User\UserId;
use App\Blog\Domain\Model\User\UserRepository;

class SessionUserRepository implements UserRepository
{
    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!array_key_exists('users', $_SESSION)) {
            $_SESSION['users'] = [];
        }
    }

    public function findAll(): array
    {
        return array_map(function ($user) {
            return User::fromRawData($user);
        }, $_SESSION['users']);
    }

    public function findById(string $id): ?User
    {
        $user = $_SESSION['users'][$id] ?? null;

        if (!$user) {
            return null;
        }

        return User::fromRawData($user);
    }

    public function save(User $user): void
    {
        $userId = $user->getId()->getValue();

        $_SESSION['users'][$userId] = [
            'id' => $userId,
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
        ];
    }

    public function delete(User $user): void
    {
        unset($_SESSION['users'][$user->getId()->getValue()]);
    }

    public function nextIdentity(): UserId
    {
        return UserId::create();
    }
}
