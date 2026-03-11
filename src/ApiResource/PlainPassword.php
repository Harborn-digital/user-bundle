<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\ApiResource;

use ApiPlatform\Metadata\ApiProperty;

/**
 * Represents the RepeatedType password field used in AccountType.
 * Both `first` and `second` must match for the password to be changed.
 * Omit (or send null) to leave the current password unchanged.
 */
class PlainPassword
{
    #[ApiProperty(description: 'The new password.')]
    public ?string $first = null;

    #[ApiProperty(description: 'The new password repeated for confirmation.')]
    public ?string $second = null;
}
