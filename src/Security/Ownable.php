<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Security;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Doctrine\Common\Collections\Collection;

interface Ownable
{
    public function getOwners(): Collection;

    public function addOwner(UserInterface $owner): void;
}
