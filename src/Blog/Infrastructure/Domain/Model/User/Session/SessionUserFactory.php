<?php

namespace App\Blog\Infrastructure\Domain\Model\User\Session;

use App\Blog\Domain\Model\User\User;
use App\Blog\Domain\Model\User\UserId;
use App\Blog\Domain\Model\User\UserFactory;

class SessionUserFactory implements UserFactory
{
    public function build(UserId $id, string $firstName, string $lastName): User
    {
        return User::writeNewFrom($id, $firstName, $lastName);
    }
}
