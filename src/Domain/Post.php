<?php

namespace App\Domain;

use App\Domain\Events\PostWasCreated;
use App\Domain\Events\PostWasRecreated;
use App\Domain\Events\PostTitleWasChanged;
use App\Domain\Events\PostContentWasChanged;

class Post extends AggregateRoot
{
    private string $id;
    private string $title;
    private string $content;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function writeNewFrom(string $title, string $content): self
    {
        $id = PostId::create();
        $post = new static($id);
        $event = new PostWasCreated($id, $title, $content);

        $post->recordApplyAndPublishThat($event);

        return $post;
    }

    public static function recreateFrom(string $id, string $title, string $content): self
    {
        $post = new static($id);
        $event = new PostWasRecreated($id, $title, $content);

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
    }

    protected function applyPostWasRecreated(PostWasRecreated $event): void
    {
        $this->id = $event->getId();
        $this->title = $event->getTitle();
        $this->content = $event->getContent();
    }

    protected function applyPostTitleWasChanged(PostTitleWasChanged $event): void
    {
        $this->title = $event->getTitle();
    }

    protected function applyPostContentWasChanged(PostContentWasChanged $event): void
    {
        $this->content = $event->getContent();
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
}
