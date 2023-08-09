<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

final class AuthenticateUserEvent extends Event implements AuthenticateUserEventInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    public function __construct(private readonly UserInterface $user, Request $request)
    {
        $this->request = $request;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
