<?php

namespace App\Domain\Subscribers;

use App\Domain\Events\DomainEvent;
use App\Domain\Events\PostWasRecreated;

class PostWasRecreatedSubscriber implements DomainEventSubscriber
{
    public function handle(DomainEvent $event): void
    {
        //
    }

    public function isSubscribedTo(DomainEvent $event): bool;
    {
        return in_array($event::class, [
            PostWasRecreated::class,
        ]);
    }
}