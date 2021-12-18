<?php

namespace App\Blog\Domain\Model\User;

interface UserFactory
{
    public function build(UserId $id, string $firstName, string $lastName): User;
}
