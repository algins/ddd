<?php

namespace App\Infrastructure\Projections\Session;

use App\Domain\Events\PostWasRecreated;

class PostWasRecreatedProjection
{
    public function listensTo(): string
    {
        return PostWasRecreated::class;
    }

    public function project(PostWasRecreated $event): void
    {
        //
    }
}
