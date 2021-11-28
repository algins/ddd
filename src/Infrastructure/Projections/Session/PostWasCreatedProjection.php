<?php

namespace App\Infrastructure\Projections\Session;

use App\Domain\Events\PostWasCreated;

class PostWasCreatedProjection
{
    public function listensTo(): string
    {
        return PostWasCreated::class;
    }

    public function project(PostWasCreated $event): void
    {
        //
    }
}