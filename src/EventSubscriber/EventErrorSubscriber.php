<?php

namespace App\EventSubscriber;

use App\Event\LoginErrorEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventErrorSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            LoginErrorEvent::class => 'onLoginError'
        ];
    }

    public function onLoginError(LoginErrorEvent $event): void
    {
        $logger = new LoggerInterface;
        $logger->info('Podano błędne dane dla użytkownika '. $event->getUser());
    }
}
