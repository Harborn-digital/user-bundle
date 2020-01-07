<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\Event;

class PostRegistrationEvent extends /* @scrutinizer ignore-deprecated */ Event implements PostRegistrationEventInterface, ResponseEventInterface
{
    /**
     * @var string
     */
    private $state;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var string
     */
    private $action;

    public function __construct(string $state, Response $response, string $action)
    {
        $this->state    = $state;
        $this->response = $response;
        $this->action   = $action;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}
