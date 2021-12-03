<?php

namespace App\Post\Domain\Subscribers;

use App\Post\Domain\Events\PostTitleWasChanged;
use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\DomainEventSubscriber;

class PostTitleWasChangedSubscriber implements DomainEventSubscriber
{
    public function handle(DomainEvent $event): void
    {
        //
    }

    public function isSubscribedTo(DomainEvent $event): bool;
    {
        return in_array($event::class, [
            PostTitleWasChanged::class,
        ]);
    }
}
