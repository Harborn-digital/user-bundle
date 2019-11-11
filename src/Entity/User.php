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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="ConnectHolland\UserBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, entityClass="ConnectHolland\UserBundle\Entity\User", message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $enabled = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $passwordRequestToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="UserOAuth", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $oauths;

    public function __construct()
    {
        $this->oauths = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @param mixed $passwordRequestToken
     *
     * @return User
     */
    public function setPasswordRequestToken($passwordRequestToken): UserInterface
    {
        $this->passwordRequestToken = $passwordRequestToken;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeImmutable $lastLogin): UserInterface
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): UserInterface
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|UserOAuth[]
     */
    public function getOAuths(): Collection
    {
        return $this->oauths;
    }

    public function addOAuth(UserOAuth $oauth): self
    {
        if (!$this->oauths->contains($oauth)) {
            $this->oauths[] = $oauth;
            $oauth->setUser($this);
        }

        return $this;
    }

    public function removeOAuth(UserOAuth $oauth): self
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
