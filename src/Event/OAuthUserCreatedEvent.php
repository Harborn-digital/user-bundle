<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use ConnectHolland\UserBundle\Entity\UserInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Contract\EventDispatcher\Event;

final class OAuthUserCreatedEvent extends Event implements OAuthUserCreatedEventInterface
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var UserResponseInterface
     */
    private $response;

    public function __construct(UserInterface $user, UserResponseInterface $response)
    {
        $this->user     = $user;
        $this->response = $response;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getResponse(): UserResponseInterface
    {
        return $this->response;
    }
}
