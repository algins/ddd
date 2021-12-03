<?php

namespace App\Post\Domain\ValueObjects;

class PostId
{
    public static function create(): string
    {
        return uniqid();
    }
}
