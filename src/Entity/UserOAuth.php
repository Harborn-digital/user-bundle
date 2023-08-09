<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'connectholland_user_user_oauth')]
#[ORM\UniqueConstraint(name: 'unique_oauth_username_resource', columns: ['resource', 'oauth_username'])]
class UserOAuth implements UserOAuthInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 180)]
    private ?string $resource = null;

    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 180)]
    private $oauthUsername;

    #[ORM\ManyToOne(targetEntity: UserInterface::class, inversedBy: 'oauths', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserInterface $user = null;

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(string $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    public function getOAuthUsername()
    {
        return $this->oauthUsername;
    }

    public function setOAuthUsername($oauthUsername)
    {
        $this->oauthUsername = $oauthUsername;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }
}
