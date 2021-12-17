<?php

namespace App\Blog\Application\Post\CreatePost;

class CreatePostRequest
{
    private string $title;
    private string $content;
    private string $authorId;

    public function __construct(string $title, string $content, string $authorId)
    {
        $this->title = $title;
        $this->content = $content;
        $this->authorId = $authorId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAuthorId(): string
    {
        return $this->authorId;
    }
}
