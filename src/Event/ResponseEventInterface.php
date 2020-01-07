<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use Symfony\Component\HttpFoundation\Response;

interface ResponseEventInterface
{
    public function getResponse(): Response;

    public function setResponse(Response $response): void;
}
