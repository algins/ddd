<?php

namespace App\Shared\Domain\Model\Event;

use DateTimeImmutable;

interface DomainEvent
{
    public function getOccuredOn(): DateTimeImmutable;
}
