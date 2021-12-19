<?php

namespace App\Blog\Domain\Model\User;

use App\Shared\Domain\Model\Event\DomainEvent;
use DateTimeImmutable;

class UserWasDeleted implements DomainEvent
{
    private UserId $id;
    private DateTimeImmutable $occuredOn;

    public function __construct(UserId $id)
    {
        $this->id = $id;
        $this->occuredOn = new DateTimeImmutable();
    }

    public function getId(): PostId
    {
        return $this->id;
    }

    public function getOccuredOn(): DateTimeImmutable
    {
        return $this->occuredOn;
    }
}
