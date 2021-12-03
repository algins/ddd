<?php

namespace App\Post\Domain\Events;

use App\Shared\Domain\DomainEvent;

class PostContentWasChanged implements DomainEvent
{
    private string $id;
    private string $content;

    public function __construct(string $id, string $content)
    {
        $this->id = $id;
        $this->content = $content;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}