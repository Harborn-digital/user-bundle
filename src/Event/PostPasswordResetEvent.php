<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class PostPasswordResetEvent extends /* @scrutinizer ignore-deprecated */ Event implements PostPasswordResetEventInterface
{
    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $action;

    public function __construct(string $state, string $action)
    {
        $this->state  = $state;
        $this->action = $action;
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
