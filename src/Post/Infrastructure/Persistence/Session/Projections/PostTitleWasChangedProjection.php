<?php

namespace App\Post\Infrastructure\Persistence\Session\Projections;

use App\Post\Domain\Events\PostTitleWasChanged;
use App\Shared\Domain\DomainEvent;
use App\Shared\Infrastructure\Persistence\Projections\Projection;

class PostTitleWasChangedProjection implements Projection
{
    public function listensTo(): string
    {
        return PostTitleWasChanged::class;
    }

    public function project(DomainEvent $event): void
    {
        //
    }
}
