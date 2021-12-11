<?php

namespace App\Blog\Domain\Model\User;

use App\Shared\Domain\Model\Event\DomainEvent;
use DateTimeImmutable;

class UserFirstNameWasChanged implements DomainEvent
{
    private UserId $id;
    private string $firstName;
    private DateTimeImmutable $occuredOn;

    public function __construct(UserId $id, string $firstName)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->occuredOn = new DateTimeImmutable();
    }

    public function getId(): PostId
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getOccuredOn(): DateTimeImmutable
    {
        return $this->occuredOn;
    }
}
