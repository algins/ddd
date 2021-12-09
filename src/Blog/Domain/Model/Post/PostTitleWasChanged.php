<?php

namespace App\Blog\Domain\Model\Post;

use App\Shared\Domain\Model\Event\DomainEvent;
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
