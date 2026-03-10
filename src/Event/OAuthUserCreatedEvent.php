<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use ConnectHolland\UserBundle\Entity\UserInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class OAuthUserCreatedEvent extends Event implements OAuthUserCreatedEventInterface
{
    /**
     * @var UserResponseInterface
     */
    private $response;

    public function __construct(private readonly UserInterface $user, UserResponseInterface $response)
    {
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
