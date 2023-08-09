<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle;

use ConnectHolland\UserBundle\Event\AuthenticateUserEvent;
use ConnectHolland\UserBundle\Event\CreateOAuthUserEvent;
use ConnectHolland\UserBundle\Event\CreateUserEvent;
use ConnectHolland\UserBundle\Event\OAuthUserCreatedEvent;
use ConnectHolland\UserBundle\Event\PasswordResetFailedEvent;
use ConnectHolland\UserBundle\Event\PostPasswordResetEvent;
use ConnectHolland\UserBundle\Event\PostRegistrationEvent;
use ConnectHolland\UserBundle\Event\ResetUserEvent;
use ConnectHolland\UserBundle\Event\UserCreatedEvent;
use ConnectHolland\UserBundle\Event\UserNotFoundEvent;
use ConnectHolland\UserBundle\Event\UserResetEvent;

final class UserBundleEvents
{
    public const AUTHENTICATE_USER        = AuthenticateUserEvent::class;
    public const CREATE_OAUTH_USER        = CreateOAuthUserEvent::class;
    public const CREATE_USER              = CreateUserEvent::class;
    public const OAUTH_USER_CREATED       = OAuthUserCreatedEvent::class;
    public const PASSWORD_RESET_COMPLETED = PostPasswordResetEvent::class;
    public const REGISTRATION_COMPLETED   = PostRegistrationEvent::class;
    public const RESET_USER               = ResetUserEvent::class;
    public const USER_CREATED             = UserCreatedEvent::class;
    public const USER_NOT_FOUND           = UserNotFoundEvent::class;
    public const USER_RESET               = UserResetEvent::class;
    public const PASSWORD_RESET_FAILED    = PasswordResetFailedEvent::class;
}
