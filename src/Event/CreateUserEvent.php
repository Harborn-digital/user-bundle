<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Symfony\Component\EventDispatcher\Event;

final class CreateUserEvent extends /** @scrutinizer ignore-deprecated */ Event implements CreateUserEventInterface
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var string
     */
    private $plainPassword;

    public function __construct(UserInterface $user, string $plainPassword)
    {
        $this->user          = $user;
        $this->plainPassword = $plainPassword;
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
