<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ControllerSuccessEvent extends Event implements ControllerSuccessEventInterface
{
    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var string
     */
    private $flashType;

    public function __construct(string $action, string $entityName, ?string $flashType = 'notice')
    {
        $this->action     = $action;
        $this->entityName = $entityName;
        $this->flashType  = $flashType;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getFlashType(): string
    {
        return $this->flashType;
    }
}
