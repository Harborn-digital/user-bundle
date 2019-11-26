<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\UserResetEventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface UserResetSubscriberInterface extends EventSubscriberInterface
{
    public function onUserReset(UserResetEventInterface $event): void;
}
