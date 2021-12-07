<?php

namespace App\Post\Infrastructure\Persistence\Session;

use App\Post\Domain\EventStore;
use App\Shared\Domain\DomainEvent;

class SessionEventStore implements EventStore
{
    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!array_key_exists('events', $_SESSION)) {
            $_SESSION['events'] = [];
        }
    }

    public function findAll(): array
    {
        return $_SESSION['events'];
    }

    public function save(DomainEvent $event): void
    {
        $_SESSION['events'][] = json_encode((array) $event);
    }
}
