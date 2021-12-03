<?php

namespace App\Post\Domain\Events;

use App\Post\Domain\ValueObjects\PostId;
use App\Shared\Domain\DomainEvent;

class PostContentWasChanged implements DomainEvent
{
    private PostId $id;
    private string $content;

    public function __construct(PostId $id, string $content)
    {
        $this->id = $id;
        $this->content = $content;
    }

    public function getId(): PostId
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
