<?php

namespace ConnectHolland\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class PostPasswordResetEvent extends Event implements PostPasswordResetEventInterface
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
