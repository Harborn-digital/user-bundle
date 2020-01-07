<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

interface PostRegistrationEventInterface
{
    public function getState(): string;

    public function getAction(): string;
}
