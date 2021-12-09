<?php

namespace App\Blog\Domain\Model\Post;

use App\Shared\Domain\Model\Event\DomainEvent;
use DateTimeImmutable;

class PostWasCreated implements DomainEvent
{
    private PostId $id;
    private string $title;
    private string $content;
    private PostAuthor $author;
    private DateTimeImmutable $occuredOn;

    public function __construct(PostId $id, string $title, string $content, PostAuthor $author)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
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

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthor(): PostAuthor
    {
        return $this->author;
    }

    public function getOccuredOn(): DateTimeImmutable
    {
        return $this->occuredOn;
    }
}
