<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\AuthenticateUserEventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface AuthenticateUserSubscriberInterface extends EventSubscriberInterface
{
    public function onAuthenticateUser(AuthenticateUserEventInterface $event): void;
}
