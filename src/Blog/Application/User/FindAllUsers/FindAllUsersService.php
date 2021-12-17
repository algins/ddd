<?php

namespace App\Blog\Application\User\FindAllUsers;

use App\Blog\Domain\Model\User\UserRepository;

class FindAllUsersService
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
            return new FindAllUsersResponse($user);
        }, $users);
    }
}
