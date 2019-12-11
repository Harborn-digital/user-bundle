<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle;

use ConnectHolland\UserBundle\Event\AuthenticateUserEvent;
use ConnectHolland\UserBundle\Event\ControllerSuccessEvent;
use ConnectHolland\UserBundle\Event\CreateOAuthUserEvent;
use ConnectHolland\UserBundle\Event\CreateUserEvent;
use ConnectHolland\UserBundle\Event\OAuthUserCreatedEvent;
use ConnectHolland\UserBundle\Event\ResetUserEvent;
use ConnectHolland\UserBundle\Event\UserCreatedEvent;
use ConnectHolland\UserBundle\Event\UserRegistrationCompletedEvent;
use ConnectHolland\UserBundle\Event\UserResetEvent;

final class UserBundleEvents
{
    const AUTHENTICATE_USER       = AuthenticateUserEvent::class;
    const CONTROLLER_SUCCESS      = ControllerSuccessEvent::class;
    const CREATE_OAUTH_USER       = CreateOAuthUserEvent::class;
    const CREATE_USER             = CreateUserEvent::class;
    const OAUTH_USER_CREATED      = OAuthUserCreatedEvent::class;
    const REGISTRATION_COMPLETED  = UserRegistrationCompletedEvent::class;
    const RESET_USER              = ResetUserEvent::class;
    const USER_CREATED            = UserCreatedEvent::class;
    const USER_RESET              = UserResetEvent::class;
}
