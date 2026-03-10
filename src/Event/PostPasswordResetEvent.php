<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class PostPasswordResetEvent extends Event implements PostPasswordResetEventInterface
{
    public function __construct(private readonly string $state, private readonly string $action)
    {
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
