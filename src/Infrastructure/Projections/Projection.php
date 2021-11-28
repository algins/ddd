<?php

namespace App\Infrastructure\Projections;

use App\Domain\Events\DomainEvent;

interface Projection
{
    public function listensTo(): string;

    public function project(DomainEvent $event): void;
}
