<?php

namespace App\Post\Domain\Subscribers;

use App\Post\Domain\Events\PostWasCreated;
use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\DomainEventSubscriber;

class PostWasCreatedSubscriber implements DomainEventSubscriber
{
    public function handle(DomainEvent $event): void
    {
        //
    }

    public function isSubscribedTo(DomainEvent $event): bool;
    {
        return in_array($event::class, [
            PostWasCreated::class,
        ]);
    }
}
