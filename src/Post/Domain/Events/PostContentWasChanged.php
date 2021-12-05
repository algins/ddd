<?php

namespace App\Post\Domain\Events;

use App\Post\Domain\ValueObjects\PostId;
use App\Shared\Domain\DomainEvent;
use DateTimeImmutable;

class PostContentWasChanged implements DomainEvent
{
    private PostId $id;
    private string $content;
    private DateTimeImmutable $occuredOn;

    public function __construct(PostId $id, string $content)
    {
        $this->id = $id;
        $this->content = $content;
        $this->occuredOn = new DateTimeImmutable();
    }

    public function getId(): PostId
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getOccuredOn(): DateTimeImmutable
    {
        return $this->occuredOn;
    }
}
