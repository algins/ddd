<?php

namespace App\Shared\Infrastructure\Persistence\Projections;

use App\Shared\Domain\Model\Event\DomainEvent;

interface Projection
{
    public function listensTo(): string;

    public function project(DomainEvent $event): void;
}
