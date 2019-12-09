<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\MappedSuperclass
 * @ORM\Table(name="connectholland_user_user")
 */
abstract class BaseUser implements UserInterface
{
    /*
     * Add timestampable behavior.
     */
    use TimestampableEntity;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    protected $enabled = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $passwordRequestToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;

    /**
     * @ORM\OneToMany(targetEntity="ConnectHolland\UserBundle\Entity\UserOAuth", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    protected $oauths;

    public function __construct()
    {
        $this->oauths = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserInterface
    {
        $this->email = $email;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): UserInterface
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getPasswordRequestToken(): ?string
    {
        return $this->passwordRequestToken;
    }

    public function setPasswordRequestToken(?string $passwordRequestToken): UserInterface
    {
        $this->passwordRequestToken = $passwordRequestToken;

        return $this;
    }

    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTime $lastLogin): UserInterface
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles   = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): UserInterface
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): UserInterface
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }

    public function getOAuths(): Collection
    {
        return $this->oauths;
    }

    public function addOAuth(UserOAuthInterface $oauth): UserInterface
    {
        if (!$this->oauths->contains($oauth)) {
            $this->oauths[] = $oauth;
            $oauth->setUser($this);
        }

        return $this;
    }

    public function removeOAuth(UserOAuthInterface $oauth): UserInterface
    {
        if ($this->oauths->contains($oauth)) {
            $this->oauths->removeElement($oauth);
            // set the owning side to null (unless already changed)
            if ($oauth->getUser() === $this) {
                $oauth->setUser(null);
            }
        }

        return $this;
    }
}
