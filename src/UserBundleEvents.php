<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle;

use ConnectHolland\UserBundle\Event\CreateUserEvent;
use ConnectHolland\UserBundle\Event\ResetUserEvent;
use ConnectHolland\UserBundle\Event\UserCreatedEvent;
use ConnectHolland\UserBundle\Event\UserResetEvent;

final class UserBundleEvents
{
    const CREATE_USER  = CreateUserEvent::class;
    const RESET_USER   = ResetUserEvent::class;
    const USER_CREATED = UserCreatedEvent::class;
    const USER_RESET   = UserResetEvent::class;
}
