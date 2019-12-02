<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Event;

use ConnectHolland\UserBundle\Entity\UserInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

interface CreateOAuthUserEventInterface
{
    public function getUser(): UserInterface;

    public function getResponse(): UserResponseInterface;
}
