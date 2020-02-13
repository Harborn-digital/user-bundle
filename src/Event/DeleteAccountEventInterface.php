<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Symfony\Component\HttpFoundation\Response;

interface DeleteAccountEventInterface
{
    public function getUser(): UserInterface;

    public function getResponse(): ?Response;

    public function setResponse(?Response $response): void;
}
