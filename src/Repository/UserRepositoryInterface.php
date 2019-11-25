<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Repository;

use ConnectHolland\UserBundle\Entity\UserInterface;

interface UserRepositoryInterface
{
    public function findOneByEmail(string $email): ?UserInterface;

    public function findOneByOAuthUsername(string $resource, string $oauthUsername): ?UserInterface;
}
