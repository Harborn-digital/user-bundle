<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    public function getEmail(): ?string;

    public function setEmail(string $email): self;

    public function setRoles(array $roles): self;

    public function isEnabled(): bool;

    public function setEnabled(bool $enabled): self;

    public function getPassword(): ?string;

    public function setPassword(string $password): self;

    public function getPasswordRequestToken(): ?string;

    public function setPasswordRequestToken(?string $passwordRequestToken): self;

    public function getLastLogin(): ?\DateTime;

    public function setLastLogin(\DateTime $lastLogin): self;

    public function getOAuths(): Collection;

    public function addOAuth(UserOAuthInterface $oauth): self;

    public function removeOAuth(UserOAuthInterface $oauth): self;
}
