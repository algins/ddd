<?php

namespace App\Blog\Application\User\FindUser;

use App\Blog\Domain\Model\User\UserDoesNotExistException;
use App\Blog\Domain\Model\User\UserRepository;

class FindUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(FindUserRequest $request): FindUserResponse
    {
        $id = $request->getId();
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new UserDoesNotExistException();
        }

        return new FindUserResponse($user);
    }
}
