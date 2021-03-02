<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultDataInterface;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Content\ResultInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class PostRegistrationEvent extends Event implements PostRegistrationEventInterface, ResponseEventInterface, ResultInterface
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

    /**
     * @var ResultDataInterface
     */
    private $resultData;

    /**
     * @var int
     */
    private $statusCode = Response::HTTP_OK;

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

    public function setResultData(ResultDataInterface $resultData): ResultInterface
    {
        $this->resultData = $resultData;

        return $this;
    }

    public function getResultData(): ?ResultDataInterface
    {
        return $this->resultData;
    }

    public function setStatusCode(int $statusCode): ResultInterface
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
