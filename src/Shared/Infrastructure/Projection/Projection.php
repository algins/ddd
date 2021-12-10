<?php

namespace App\Shared\Infrastructure\Projection;

use App\Shared\Domain\Model\Event\DomainEvent;

interface Projection
{
    public function listensTo(): string;

    public function project(DomainEvent $event): void;
}
