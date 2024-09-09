<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class LoginErrorEvent extends Event
{
    const NAME = 'login.error';

    public function __construct(public readonly string $user)
    {
    }
}
