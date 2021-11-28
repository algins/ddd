<?php

namespace App\Domain;

class PostId
{
    public static function create(): string
    {
        return uniqid();
    }
}