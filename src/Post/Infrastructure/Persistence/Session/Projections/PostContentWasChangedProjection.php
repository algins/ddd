<?php

namespace App\Post\Infrastructure\Persistence\Session\Projections;

use App\Post\Domain\Events\PostContentWasChanged;
use App\Shared\Domain\DomainEvent;
use App\Shared\Infrastructure\Persistence\Projections\Projection;

class PostContentWasChangedProjection implements Projection
{
    public function listensTo(): string
    {
        return PostContentWasChanged::class;
    }

    public function project(DomainEvent $event): void
    {
        //
    }
}
