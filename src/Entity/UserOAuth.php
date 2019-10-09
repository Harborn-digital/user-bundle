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
 */
class UserOAuth
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $resource;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $oauthUsername;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="oauths")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return UserOAuth
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
    public function getOauthUsername()
    {
        return $this->oauthUsername;
    }

    /**
     * @param mixed $oauthUsername
     *
     * @return UserOAuth
     */
    public function setOauthUsername($oauthUsername)
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
