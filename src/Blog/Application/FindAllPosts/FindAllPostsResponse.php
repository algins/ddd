<?php

namespace App\Blog\Application\FindAllPosts;

use App\Blog\Domain\Model\Post\Post;

class FindAllPostsResponse
{
    private string $id;
    private string $title;
    private string $content;
    private string $author;

    public function __construct(Post $post)
    {
        $this->id = $post->getId()->getValue();
        $this->title = $post->getTitle();
        $this->content = $post->getContent();
        $this->author = $post->getAuthor()->getFirstName() . ' ' . $post->getAuthor()->getLastName();
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

    public function getAuthor(): string
    {
        return $this->author;
    }
}
