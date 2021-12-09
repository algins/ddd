<?php

namespace App\Blog\Infrastructure\Persistence\Session\Projections;

use App\Blog\Domain\Model\Post\PostWasRecreated;
use App\Shared\Domain\Model\Event\DomainEvent;
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
