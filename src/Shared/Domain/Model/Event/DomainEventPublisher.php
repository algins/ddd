<?php

namespace App\Shared\Domain\Model\Event;

class DomainEventPublisher
{
    private array $subscribers;
    private static ?DomainEventPublisher $instance = null;

    private function __construct()
    {
        return $this->subscribers = [];
    }

    public static function instance(): DomainEventPublisher
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function subscribe(DomainEventSubscriber $domainEventSubscriber): void
    {
        $this->subscribers[] = $domainEventSubscriber;
    }

    public function publish(DomainEvent $event): void
    {
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->isSubscribedTo($event)) {
                $subscriber->handle($event);
            }
        }
    }
}
