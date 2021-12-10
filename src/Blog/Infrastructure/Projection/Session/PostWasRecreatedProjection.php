<?php

namespace App\Blog\Infrastructure\Projection\Session;

use App\Blog\Domain\Model\Post\PostWasRecreated;
use App\Shared\Domain\Model\Event\DomainEvent;
use App\Shared\Infrastructure\Projection\Projection;

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
