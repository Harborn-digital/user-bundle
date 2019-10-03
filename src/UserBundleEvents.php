<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle;

use ConnectHolland\UserBundle\Event\CreateUserEvent;
use ConnectHolland\UserBundle\Event\UserCreatedEvent;

class UserBundleEvents
{
    const CREATE_USER  = CreateUserEvent::class;
    const USER_CREATED = UserCreatedEvent::class;
}
