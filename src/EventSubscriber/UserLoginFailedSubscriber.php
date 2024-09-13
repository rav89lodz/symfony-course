<?php

namespace App\EventSubscriber;

use App\Event\UserLoginFailedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserLoginFailedSubscriber  implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserLoginFailedEvent::NAME => 'onUserLoginFailed',
        ];
    }

    public function onUserLoginFailed(UserLoginFailedEvent $event): void
    {
        $this->logger->warning(sprintf(
            '==>> Failed login attempt for user: %s at %s',
            $event->getUsername(),
            $event->getFailedAt()->format('Y-m-d H:i:s')
        ));
    }
}
