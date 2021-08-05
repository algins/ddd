<?php

namespace App;

/**
* Repository (Model layer). 
* Represents whole collection of blog posts available.
*/
class PostRepository
{
    private array $posts;

    public function __construct()
    {
        $this->posts = [
            Post::writeNewFrom('Title 1', 'Content 1'),
            Post::writeNewFrom('Title 2', 'Content 2'),
            Post::writeNewFrom('Title 3', 'Content 3'),
        ];
    }

    public function findAll(): array
    {
        return $this->posts;
    }
}