<?php

namespace App\Blog\Domain\Model\Post;

use App\Blog\Domain\Model\User\UserId;
use App\Shared\Domain\Model\Event\DomainEvent;
use DateTimeImmutable;

class PostWasCreated implements DomainEvent
{
    private PostId $id;
    private string $title;
    private string $content;
    private UserId $authorId;
    private DateTimeImmutable $occuredOn;

    public function __construct(PostId $id, string $title, string $content, UserId $authorId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->authorId = $authorId;
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

    public function getAuthorId(): UserId
    {
        return $this->authorId;
    }

    public function getOccuredOn(): DateTimeImmutable
    {
        return $this->occuredOn;
    }
}
