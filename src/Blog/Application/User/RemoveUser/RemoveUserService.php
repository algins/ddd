<?php

namespace App\Blog\Application\User\RemoveUser;

use App\Blog\Domain\Model\User\UserDoesNotExistException;
use App\Blog\Domain\Model\User\UserRepository;

class RemoveUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(RemoveUserRequest $request): void
    {
        $id = $request->getId();
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new UserDoesNotExistException();
        }

        $user->markAsDeleted();

        $this->userRepository->save($user);
    }
}
