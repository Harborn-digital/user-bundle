<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\ResetUserEventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface ResetUserSubscriberInterface extends EventSubscriberInterface
{
    public function onResetUser(ResetUserEventInterface $event): void;
}
