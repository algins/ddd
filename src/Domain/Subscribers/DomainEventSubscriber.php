<?php

namespace App\Domain\Subscribers;

use App\Domain\Events\DomainEvent;

interface DomainEventSubscriber
{
    public function handle(DomainEvent $event): void;

    public function isSubscribedTo(DomainEvent $event): bool;
}
