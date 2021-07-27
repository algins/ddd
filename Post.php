<?php

/**
* MODEL LAYER
* -----------
* Represents blog post.
*/
class Post
{
    private string $title;
    private string $content;

    // Named constructor pattern
    public static function writeNewFrom(string $title, string $content): static
    {
        return new static($title, $content);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }

    private function __construct(string $title, string $content)
    {
        $this->setTitle($title);
        $this->setContent($content);
    }

    private function setTitle(string $title): void
    {
        if (empty($title)) {
            throw new RuntimeException('Title cannot be empty');
        }

        $this->title = $title;
    }

    private function setContent(string $content): void
    {
        if (empty($content)) {
            throw new RuntimeException('Content cannot be empty');
        }

        $this->content = $content;
    }
}