<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\Event;

class UserNotFoundEvent extends /* @scrutinizer ignore-deprecated */ Event implements UserNotFoundEventInterface, ResponseEventInterface
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $action;

    public function __construct(Response $response, string $state, string $action)
    {
        $this->response = $response;
        $this->state    = $state;
        $this->action   = $action;
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
