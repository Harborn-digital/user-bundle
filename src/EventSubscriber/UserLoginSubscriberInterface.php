<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

interface UserLoginSubscriberInterface extends EventSubscriberInterface
{
    public function onUserLogin(InteractiveLoginEvent $event): void;
}
