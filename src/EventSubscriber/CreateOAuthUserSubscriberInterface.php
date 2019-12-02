<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\CreateOAuthUserEventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface CreateOAuthUserSubscriberInterface extends EventSubscriberInterface
{
    public function onCreateOAuthUser(CreateOAuthUserEventInterface $event): void;
}
