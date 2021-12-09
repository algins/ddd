<?php

namespace App\Blog\Domain\Event;

use App\Shared\Domain\DomainEventSubscriber;
use App\Shared\Domain\Model\Event\DomainEvent;
use App\Shared\Domain\Model\Event\EventStore;

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
