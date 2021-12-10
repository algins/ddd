<?php

namespace App\Blog\Infrastructure\Projection\Session;

use App\Blog\Domain\Model\Post\PostTitleWasChanged;
use App\Shared\Domain\Model\Event\DomainEvent;
use App\Shared\Infrastructure\Projection\Projection;

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
