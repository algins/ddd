<?php

namespace App\Blog\Application\Post\FindAllPosts;

use App\Blog\Domain\Model\Post\Post;
use App\Blog\Domain\Model\User\User;

class FindAllPostsResponse
{
    private string $id;
    private string $title;
    private string $content;
    private string $authorName;

    public function __construct(Post $post, User $author)
    {
        $this->id = $post->getId()->getValue();
        $this->title = $post->getTitle();
        $this->content = $post->getContent();
        $this->authorName = $author->getFirstName() . ' ' . $author->getLastName();
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

    public function getAuthorName(): string
    {
        return $this->authorName;
    }
}
