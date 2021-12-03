<?php

namespace App\Shared\Domain;

interface DomainEventSubscriber
{
    public function handle(DomainEvent $event): void;

    public function isSubscribedTo(DomainEvent $event): bool;
}
