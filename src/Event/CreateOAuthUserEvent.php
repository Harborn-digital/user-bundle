<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use ConnectHolland\UserBundle\Entity\UserInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\EventDispatcher\Event;

final class CreateOAuthUserEvent extends /* @scrutinizer ignore-deprecated */ Event implements CreateOAuthUserEventInterface
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var UserResponseInterface
     */
    private $response;

    public function __construct(string $userClass, UserResponseInterface $response)
    {
        if (is_subclass_of($userClass, UserInterface::class) === false) {
            throw new \InvalidArgumentException(sprintf('The $userClass should implement %s, but %s given.', UserInterface::class, $userClass));
        }

        $this->user     = new $userClass();
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
