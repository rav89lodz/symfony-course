<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class LoginErrorEvent extends Event
{
    const NAME = 'login.error';

    public function __construct(private string $user)
    {
    }

    public function getUser(): string
    {
        return $this->user;
    }
}
