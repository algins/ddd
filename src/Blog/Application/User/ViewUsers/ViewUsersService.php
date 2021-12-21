<?php

namespace App\Blog\Application\User\ViewUsers;

use App\Blog\Domain\Model\User\UserRepository;

class ViewUsersService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(): array
    {
        $users = $this->userRepository->findAll();

        return array_map(function ($user) {
            return new ViewUsersResponse($user);
        }, $users);
    }
}
