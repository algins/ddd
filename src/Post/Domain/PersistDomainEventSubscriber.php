<?php

namespace App\Post\Domain;

use App\Post\Domain\EventStore;
use App\Shared\Domain\DomainEvent;
use App\Shared\Domain\DomainEventSubscriber;

class PersistDomainEventSubscriber implements DomainEventSubscriber
{
    private EventStore $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function handle(DomainEvent $event): void
    {
        $this->eventStore->save($event);
    }

    public function isSubscribedTo(DomainEvent $domainEvent): bool
    {
        return true;
    }
}
