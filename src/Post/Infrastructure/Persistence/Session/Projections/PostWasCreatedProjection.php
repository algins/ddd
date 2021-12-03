<?php

namespace App\Post\Infrastructure\Persistence\Session\Projections;

use App\Post\Domain\Events\PostWasCreated;
use App\Shared\Domain\DomainEvent;
use App\Shared\Infrastructure\Persistence\Projections\Projection;

class PostWasCreatedProjection implements Projection
{
    public function listensTo(): string
    {
        return PostWasCreated::class;
    }

    public function project(DomainEvent $event): void
    {
        //
    }
}
