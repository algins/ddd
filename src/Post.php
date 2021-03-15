<?php

namespace App;

/**
* Represent a blog post.
*/
class Post 
{ 
    private $title;
    private $body;

    private function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    public static function writeNewFrom($title, $body)
    {
        return new static($title, $content);
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getBody()
    {
        return $this->body;
    }
}