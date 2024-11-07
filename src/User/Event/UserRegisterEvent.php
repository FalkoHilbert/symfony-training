<?php

namespace App\User\Event;

use Symfony\Contracts\EventDispatcher\Event;

class UserRegisterEvent extends Event
{
    public function __construct(
        private int $userId,
    )
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

}