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
        $activeUsers = array_filter($_SESSION['users'], fn($user) => !$user['deleted_at']);

        return array_map(fn($user) => User::fromRawData($user), $activeUsers);
    }

    public function findById(string $id): ?User
    {
        $user = $_SESSION['users'][$id] ?? null;

        if (!$user) {
            return null;
        }

        if ($user['deleted_at']) {
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
            'deleted_at' => $user->getDeletedAt(),
        ];
    }

    public function nextIdentity(): UserId
    {
        return UserId::create();
    }
}
