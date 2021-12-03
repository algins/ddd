<?php

namespace App\Post\Domain\Events;

use App\Post\Domain\ValueObjects\PostId;
use App\Shared\Domain\DomainEvent;

class PostTitleWasChanged implements DomainEvent
{
    private PostId $id;
    private string $title;

    public function __construct(PostId $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getId(): PostId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
