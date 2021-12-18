<?php

namespace App\Blog\Infrastructure\Domain\Model\Post\Session;

use App\Blog\Domain\Model\Post\Post;
use App\Blog\Domain\Model\Post\PostId;
use App\Blog\Domain\Model\Post\PostFactory;
use App\Blog\Domain\Model\User\UserId;

class SessionPostFactory implements PostFactory
{
    public function build(PostId $id, string $title, string $content, UserId $authorId): Post
    {
        return Post::writeNewFrom($id, $title, $content, $authorId);
    }
}
