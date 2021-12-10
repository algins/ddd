<?php

namespace App\Shared\Infrastructure\Domain\Model\Event\Session;

use App\Shared\Domain\Model\Event\EventStore;
use App\Shared\Domain\Model\Event\DomainEvent;

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
