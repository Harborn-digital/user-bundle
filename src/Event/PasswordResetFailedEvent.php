<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class PasswordResetFailedEvent extends Event implements ResponseEventInterface, PasswordResetFailedEventInterface
{
    public function __construct(private Response $response, private readonly string $state, private readonly string $action)
    {
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}
