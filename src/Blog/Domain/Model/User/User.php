<?php

namespace App\Blog\Domain\Model\User;

use App\Blog\Domain\Model\Post\Post;
use App\Blog\Domain\Model\Post\PostId;
use App\Shared\Domain\Model\Aggregate\AggregateRoot;
use DateTimeImmutable;
use InvalidArgumentException;

class User extends AggregateRoot
{
    private UserId $id;
    private string $firstName;
    private string $lastName;
    private ?DateTimeImmutable $deletedAt;

    private function __construct(UserId $id)
    {
        $this->setId($id);
        $this->setDeletedAt(null);
    }

    public static function writeNewFrom(UserId $id, string $firstName, string $lastName): self
    {
        $user = new static($id);
        $event = new UserWasCreated($id, $firstName, $lastName);

        $user->recordApplyAndPublishThat($event);

        return $user;
    }

    public static function fromRawData(array $data): self
    {
        $id = UserId::create($data['id']);
        $user = new static($id);
        $event = new UserWasCreated($id, $data['first_name'], $data['last_name']);

        $user->recordApplyAndPublishThat($event);

        return $user;
    }

    public function changeFirstNameFor(string $newFirstName): void
    {
        $event = new UserFirstNameWasChanged($this->id, $newFirstName);

        $this->recordApplyAndPublishThat($event);
    }

    public function changeLastNameFor(string $newLastName): void
    {
        $event = new UserLastNameWasChanged($this->id, $newLastName);

        $this->recordApplyAndPublishThat($event);
    }

    public function markAsDeleted(): void
    {
        $event = new UserWasDeleted($this->id);

        $this->recordApplyAndPublishThat($event);
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->setId($event->getId());
        $this->setFirstName($event->getFirstName());
        $this->setLastName($event->getLastName());
    }

    protected function applyUserFirstNameWasChanged(UserFirstNameWasChanged $event): void
    {
        $this->setFirstName($event->getFirstName());
    }

    protected function applyUserLastNameWasChanged(UserLastNameWasChanged $event): void
    {
        $this->setLastName($event->getLastName());
    }

    protected function applyUserWasDeleted(UserWasDeleted $event): void
    {
        $this->setDeletedAt(new DateTimeImmutable());
    }

    private function setId(UserId $id): void
    {
        $this->id = $id;
    }

    private function setFirstName(string $firstName): void
    {
        $this->assertFirstNameIsNotEmpty($firstName);
        $this->firstName = $firstName;
    }

    private function setLastName(string $lastName): void
    {
        $this->assertLastNameIsNotEmpty($lastName);
        $this->lastName = $lastName;
    }

    private function setDeletedAt(?DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function createPost(PostId $id, string $title, string $content): Post
    {
        return Post::writeNewFrom($id, $title, $content, $this->id);
    }

    private function assertFirstNameIsNotEmpty(string $firstName): void
    {
        if (empty($firstName)) {
            throw new InvalidArgumentException('Empty first name');
        }
    }

    private function assertLastNameIsNotEmpty(string $lastName): void
    {
        if (empty($lastName)) {
            throw new InvalidArgumentException('Empty last name');
        }
    }
}
