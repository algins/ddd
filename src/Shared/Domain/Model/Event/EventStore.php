<?php

namespace App\Shared\Domain\Model\Event;

interface EventStore
{
    public function findAll(): array;

    public function save(DomainEvent $event): void;
}
