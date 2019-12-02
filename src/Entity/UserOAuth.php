<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="connectholland_user_user_oauth",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="unique_oauth_username_resource",
 *             columns={"resource", "oauth_username"})
 *     }
 * )
 */
class UserOAuth implements UserOAuthInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=180)
     */
    private $resource;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=180)
     */
    private $oauthUsername;

    /**
     * @ORM\ManyToOne(targetEntity="ConnectHolland\UserBundle\Entity\UserInterface", inversedBy="oauths", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(string $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    public function getOAuthUsername(): ?string
    {
        return $this->oauthUsername;
    }

    public function setOAuthUsername(string $oauthUsername)
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
