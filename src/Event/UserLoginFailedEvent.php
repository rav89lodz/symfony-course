<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class UserLoginFailedEvent extends Event
{
    const NAME = 'user.login_failed';

    private string $username;
    private \DateTimeInterface $failedAt;

    public function __construct(string $username)
    {
        $this->username = $username;
        $this->failedAt = new \DateTimeImmutable();
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFailedAt(): \DateTimeInterface
    {
        return $this->failedAt;
    }
}
