<?php

namespace App\Domain\Events;

use App\Domain\Author;

class PostWasRecreated implements DomainEvent
{
    private string $id;
    private string $title;
    private string $content;
    private Author $author;

    public function __construct(string $id, string $title, string $content, Author $author)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
    }

    public function getId(): string
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

    public function getAuthor(): Author
    {
        return $this->author;
    }
}
