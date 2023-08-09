<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class DeleteAccountEvent extends Event implements DeleteAccountEventInterface
{
    /**
     * @var Response
     */
    private $response;

    public function __construct(private readonly UserInterface $user)
    {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }
}
