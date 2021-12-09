<?php

namespace App\Blog\Infrastructure\Persistence\Session\Projections;

use App\Blog\Domain\Model\Post\PostWasCreated;
use App\Shared\Domain\Model\Event\DomainEvent;
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
