<?php

namespace App\Blog\Application\User\DeleteUser;

use App\Blog\Domain\Model\User\UserDoesNotExistException;
use App\Blog\Domain\Model\User\UserRepository;

class DeleteUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(DeleteUserRequest $request): void
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
