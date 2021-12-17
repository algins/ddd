<?php

namespace App\Blog\Domain\Model\Post;

use App\Blog\Domain\Model\User\UserId;
use App\Shared\Domain\Model\Aggregate\AggregateRoot;
use InvalidArgumentException;

class Post extends AggregateRoot
{
    private PostId $id;
    private string $title;
    private string $content;
    private UserId $authorId;

    private function __construct(PostId $id)
    {
        $this->setId($id);
    }

    public static function writeNewFrom(string $title, string $content, UserId $authorId): self
    {
        $id = PostId::create();
        $post = new static($id);
        $event = new PostWasCreated($id, $title, $content, $authorId);

        $post->recordApplyAndPublishThat($event);

        return $post;
    }

    public static function fromRawData(array $data): self
    {
        $id = PostId::create($data['id']);
        $post = new static($id);
        $authorId = UserId::create($data['author_id']);
        $event = new PostWasCreated($id, $data['title'], $data['content'], $authorId);

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
        $this->setId($event->getId());
        $this->setTitle($event->getTitle());
        $this->setContent($event->getContent());
        $this->setAuthorId($event->getAuthorId());
    }

    protected function applyPostTitleWasChanged(PostTitleWasChanged $event): void
    {
        $this->setTitle($event->getTitle());
    }

    protected function applyPostContentWasChanged(PostContentWasChanged $event): void
    {
        $this->setContent($event->getContent());
    }

    private function setId(PostId $id): void
    {
        $this->id = $id;
    }

    private function setTitle(string $title): void
    {
        $this->assertTitleIsNotEmpty($title);
        $this->title = $title;
    }

    private function setContent(string $content): void
    {
        $this->assertContentIsNotEmpty($content);
        $this->content = $content;
    }

    private function setAuthorId(UserId $authorId): void
    {
        $this->authorId = $authorId;
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

    private function assertTitleIsNotEmpty(string $title): void
    {
        if (empty($title)) {
            throw new InvalidArgumentException('Empty title');
        }
    }

    private function assertContentIsNotEmpty(string $content): void
    {
        if (empty($content)) {
            throw new InvalidArgumentException('Empty content');
        }
    }
}
