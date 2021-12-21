<?php

namespace App\Blog\Application\User\ViewUser;

use App\Blog\Domain\Model\User\UserDoesNotExistException;
use App\Blog\Domain\Model\User\UserRepository;

class ViewUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(ViewUserRequest $request): ViewUserResponse
    {
        $id = $request->getId();
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new UserDoesNotExistException();
        }

        return new ViewUserResponse($user);
    }
}
