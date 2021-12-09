<?php

namespace App\Blog\UI\API\Controllers;

use App\Shared\Domain\Model\Event\EventStore;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EventController
{
    private EventStore $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function index(Request $request, Response $response): Response
    {
        $events = $this->eventStore->findAll();

        return $response->withJson($events);
    }
}
