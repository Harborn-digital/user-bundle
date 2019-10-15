<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Repository;

use ConnectHolland\UserBundle\Entity\User;

interface UserRepositoryInterface
{
    public function findOneByEmail(string $email): ?User;

    public function findOneByOAuthUsername(string $resource, string $oauthUsername): ?User;
}
