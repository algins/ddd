<?php

namespace App\Post\Domain;

use App\Post\Domain\Events\PostWasCreated;
use App\Post\Domain\Events\PostTitleWasChanged;
use App\Post\Domain\Events\PostContentWasChanged;
use App\Post\Domain\ValueObjects\PostAuthor;
use App\Post\Domain\ValueObjects\PostId;
use App\Shared\Domain\AggregateRoot;

class Post extends AggregateRoot
{
    private PostId $id;
    private string $title;
    private string $content;
    private PostAuthor $author;

    private function __construct(PostId $id)
    {
        $this->id = $id;
    }

    public static function writeNewFrom(string $title, string $content, PostAuthor $author): self
    {
        $id = PostId::create();
        $post = new static($id);
        $event = new PostWasCreated($id, $title, $content, $author);

        $post->recordApplyAndPublishThat($event);

        return $post;
    }

    public static function fromRawData(array $data): self
    {
        $id = PostId::create($data['id']);
        $post = new static($id);
        $author = new PostAuthor($data['author_first_name'], $data['author_last_name']);
        $event = new PostWasCreated($id, $data['title'], $data['content'], $author);

        $post->recordApplyAndPublishThat($event);

        return $post;
    }

    public function changeTitleFor(string $newTitle): void
    {
        $event = new PostTitleWasChanged($this->id, $newTitle);

        $this->recordApplyAndPublishThat($event);
    }

    public function changeContentFor(string $newContent): void
    {
        $event = new PostContentWasChanged($this->id, $newContent);

        $this->recordApplyAndPublishThat($event);
    }

    protected function applyPostWasCreated(PostWasCreated $event): void
    {
        $this->id = $event->getId();
        $this->title = $event->getTitle();
        $this->content = $event->getContent();
        $this->author = $event->getAuthor();
    }

    protected function applyPostWasRecreated(PostWasRecreated $event): void
    {
        $this->id = $event->getId();
        $this->title = $event->getTitle();
        $this->content = $event->getContent();
        $this->author = $event->getAuthor();
    }

    protected function applyPostTitleWasChanged(PostTitleWasChanged $event): void
    {
        $this->title = $event->getTitle();
    }

    protected function applyPostContentWasChanged(PostContentWasChanged $event): void
    {
        $this->content = $event->getContent();
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
