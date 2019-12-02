<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Entity;

interface UserOAuthInterface
{
    public function getResource(): ?string;

    public function setResource(string $resource);

    public function getOAuthUsername(): ?string;

    public function setOAuthUsername(string $oauthUsername);

    public function getUser(): ?UserInterface;

    public function setUser(?UserInterface $user);
}
