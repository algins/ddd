<?php

namespace App\Shared\Domain;

use DateTimeImmutable;

interface DomainEvent
{
    public function getOccuredOn(): DateTimeImmutable;
}
