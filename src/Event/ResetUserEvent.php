<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class ResetUserEvent extends Event implements ResetUserEventInterface
{
    public function __construct(private string $email)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
