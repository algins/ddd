<?php

namespace App\Infrastructure\Projections;

use App\Domain\Events\DomainEvent;

class Projector
{
    private array $projections = [];

    public function register(array $projections): void
    {
        foreach ($projections as $projection) {
            $this->projections[$projection->listensTo()] = $projection;
        }
    }

    public function project(array $events): void
    {
        foreach ($events as $event) {
            if (isset($this->projections[get_class($event)])) {
                $this->projections[get_class($event)]->project($event);
            }
        }
    }
}
