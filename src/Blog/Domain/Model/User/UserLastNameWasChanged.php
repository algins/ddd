<?php

namespace App\Blog\Domain\Model\User;

use App\Shared\Domain\Model\Event\DomainEvent;
use DateTimeImmutable;

class UserLastNameWasChanged implements DomainEvent
{
    private UserId $id;
    private string $lastName;
    private DateTimeImmutable $occuredOn;

    public function __construct(UserId $id, string $lastName)
    {
        $this->id = $id;
        $this->lastName = $lastName;
        $this->occuredOn = new DateTimeImmutable();
    }

    public function getId(): PostId
    {
        return $this->id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getOccuredOn(): DateTimeImmutable
    {
        return $this->occuredOn;
    }
}
