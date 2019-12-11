<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use Symfony\Component\Form\FormInterface;

interface ControllerSuccessEventInterface
{
    public function getAction(): string;

    public function getEntityName(): string;
}
