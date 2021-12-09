<?php

namespace App\Shared\Domain;

use App\Shared\Domain\Model\Event\DomainEvent;

interface DomainEventSubscriber
{
    public function handle(DomainEvent $event): void;

    public function isSubscribedTo(DomainEvent $event): bool;
}
