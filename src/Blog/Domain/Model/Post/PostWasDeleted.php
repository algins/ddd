<?php

namespace App\Blog\Domain\Model\Post;

use App\Shared\Domain\Model\Event\DomainEvent;
use DateTimeImmutable;

class PostWasDeleted implements DomainEvent
{
    private PostId $id;
    private DateTimeImmutable $occuredOn;

    public function __construct(PostId $id)
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
