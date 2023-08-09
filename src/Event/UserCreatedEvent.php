<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class UserCreatedEvent extends Event implements UserCreatedEventInterface
{
    public function __construct(private UserInterface $user)
    {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
