<?php

namespace App\Post\Domain\Events;

use App\Shared\Domain\DomainEvent;

class PostTitleWasChanged implements DomainEvent
{
    private string $id;
    private string $title;

    public function __construct(string $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
