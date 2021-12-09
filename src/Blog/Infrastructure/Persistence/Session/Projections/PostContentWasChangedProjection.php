<?php

namespace App\Blog\Infrastructure\Persistence\Session\Projections;

use App\Blog\Domain\Model\Post\PostContentWasChanged;
use App\Shared\Domain\Model\Event\DomainEvent;
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
