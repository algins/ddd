<?php

namespace App\Post\Domain\Events;

use App\Post\Domain\ValueObjects\PostAuthor;
use App\Post\Domain\ValueObjects\PostId;
use App\Shared\Domain\DomainEvent;

class PostWasCreated implements DomainEvent
{
    private PostId $id;
    private string $title;
    private string $content;
    private PostAuthor $author;

    public function __construct(PostId $id, string $title, string $content, PostAuthor $author)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
    }

    public function getId(): PostId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthor(): PostAuthor
    {
        return $this->author;
    }
}
