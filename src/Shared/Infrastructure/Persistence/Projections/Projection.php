<?php

namespace App\Shared\Infrastructure\Persistence\Projections;

use App\Shared\Domain\DomainEvent;

interface Projection
{
    public function listensTo(): string;

    public function project(DomainEvent $event): void;
}
