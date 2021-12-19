<?php

namespace App\Blog\Application\User\UpdateUser;

use App\Blog\Domain\Model\User\UserDoesNotExistException;
use App\Blog\Domain\Model\User\UserRepository;
use InvalidArgumentException;

class UpdateUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(UpdateUserRequest $request): void
    {
        $id = $request->getId();
        $firstName = $request->getFirstName();
        $lastName = $request->getLastName();

        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new UserDoesNotExistException();
        }

        $user->changeFirstNameFor($firstName);
        $user->changeLastNameFor($lastName);

        $this->userRepository->save($user);
    }
}
