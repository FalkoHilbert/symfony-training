<?php

namespace App\User\Event;

class UserRegisterEvent
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