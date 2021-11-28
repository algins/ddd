<?php

namespace App\Infrastructure\Projections\Session;

use App\Domain\Events\PostContentWasChanged;

class PostContentWasChangedProjection
{
    public function listensTo(): string
    {
        return PostContentWasChanged::class;
    }

    public function project(PostContentWasChanged $event): void
    {
        //
    }
}
