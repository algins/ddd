<?php

namespace App\Post\Domain\Events;

use App\Post\Domain\ValueObjects\PostId;
use App\Shared\Domain\DomainEvent;
use DateTimeImmutable;

class PostTitleWasChanged implements DomainEvent
{
    private PostId $id;
    private string $title;
    private DateTimeImmutable $occuredOn;

    public function __construct(PostId $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
        $this->occuredOn = new DateTimeImmutable();
    }

    public function getId(): PostId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getOccuredOn(): DateTimeImmutable
    {
        return $this->occuredOn;
    }
}
