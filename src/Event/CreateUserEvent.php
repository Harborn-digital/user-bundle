<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class CreateUserEvent extends Event implements CreateUserEventInterface
{
    public function __construct(private UserInterface $user, private string $plainPassword)
    {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}
