<?php

namespace App\Blog\Application\User\CreateUser;

use App\Blog\Domain\Model\User\User;
use App\Blog\Domain\Model\User\UserRepository;

class CreateUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(CreateUserRequest $request): void
    {
        $firstName = $request->getFirstName();
        $lastName = $request->getLastName();
        $user = User::writeNewFrom($firstName, $lastName);

        $this->userRepository->save($user);
    }
}
