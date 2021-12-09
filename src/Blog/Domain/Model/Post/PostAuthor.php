<?php

namespace App\Blog\Domain\Model\Post;

use InvalidArgumentException;

class PostAuthor
{
    private string $firstName;
    private string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
    }

    private function setFirstName(string $firstName): void
    {
        $this->assertFirstNameIsNotEmpty($firstName);
        $this->firstName = $firstName;
    }

    private function setLastName(string $lastName): void
    {
        $this->assertLastNameIsNotEmpty($lastName);
        $this->lastName = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    private function assertFirstNameIsNotEmpty(string $firstName): void
    {
        if (empty($firstName)) {
            throw new InvalidArgumentException('Empty first name');
        }
    }

    private function assertLastNameIsNotEmpty(string $lastName): void
    {
        if (empty($lastName)) {
            throw new InvalidArgumentException('Empty last name');
        }
    }
}
