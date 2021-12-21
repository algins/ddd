<?php

namespace App\Blog\Application\User\MakeUser;

use App\Blog\Domain\Model\User\User;
use App\Blog\Domain\Model\User\UserFactory;
use App\Blog\Domain\Model\User\UserRepository;

class MakeUserService
{
    private UserFactory $userFactory;
    private UserRepository $userRepository;

    public function __construct(UserFactory $userFactory, UserRepository $userRepository)
    {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    public function execute(MakeUserRequest $request): void
    {
        $firstName = $request->getFirstName();
        $lastName = $request->getLastName();
        $user = $this->userFactory->build($this->userRepository->nextIdentity(), $firstName, $lastName);

        $this->userRepository->save($user);
    }
}
