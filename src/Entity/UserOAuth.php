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
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="unique_oauth_username_resource",
 *            columns={"resource", "oauth_username"})
 *    }
 * )
 */
class UserOAuth
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=180)
     */
    private $resource;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $oauthUsername;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="oauths", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     *
     * @return UserOAuth
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOAuthUsername()
    {
        return $this->oauthUsername;
    }

    /**
     * @param mixed $oauthUsername
     *
     * @return UserOAuth
     */
    public function setOAuthUsername($oauthUsername)
    {
        $this->oauthUsername = $oauthUsername;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
