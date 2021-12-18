<?php

namespace App\Blog\Domain\Model\Post;

use App\Blog\Domain\Model\User\UserId;

interface PostFactory
{
    public function build(PostId $id, string $title, string $content, UserId $authorId): Post;
}
