<?php

namespace App\Post\Infrastructure\Persistence\Session\Projections;

use App\Post\Domain\Events\PostWasRecreated;
use App\Shared\Domain\DomainEvent;
use App\Shared\Infrastructure\Persistence\Projections\Projection;

class PostWasRecreatedProjection implements Projection
{
    public function listensTo(): string
    {
        return PostWasRecreated::class;
    }

    public function project(DomainEvent $event): void
    {
        //
    }
}
