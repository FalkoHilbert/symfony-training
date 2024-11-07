<?php

namespace App\User\EventListener;

use App\User\Event\UserRegisterEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final class UserRegisteredListener
{
    public function __invoke(UserRegisterEvent $event): void
    {
        throw new \Exception('Not implemented yet');
    }
}
