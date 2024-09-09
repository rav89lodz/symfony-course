<?php

namespace App\Event;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventErrorListener implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function logError(LoginErrorEvent $event): void
    {
        $this->logger->info('Podano błędne dane dla użytkownika '. $event->user);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginErrorEvent::class => 'logError'
        ];
    }
}
