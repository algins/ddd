<?php

namespace App\Infrastructure\Projections\Session;

use App\Domain\Events\PostTitleWasChanged;

class PostTitleWasChangedProjection
{
    public function listensTo(): string
    {
        return PostTitleWasChanged::class;
    }

    public function project(PostTitleWasChanged $event): void
    {
        //
    }
}