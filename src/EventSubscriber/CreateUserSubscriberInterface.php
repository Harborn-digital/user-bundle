<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\CreateUserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface CreateUserSubscriberInterface extends EventSubscriberInterface
{
    public function onCreateUser(CreateUserEvent $event): void;
}
