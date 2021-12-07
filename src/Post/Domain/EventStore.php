<?php

namespace App\Post\Domain;

use App\Shared\Domain\DomainEvent;

interface EventStore
{
    public function findAll(): array;

    public function save(DomainEvent $event): void;
}
