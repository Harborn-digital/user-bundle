<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber\Doctrine;

use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Security\Ownable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]
class OwnableSubscriber
{
    public function __construct(private readonly ?TokenStorageInterface $tokenStorage = null)
    {
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $this->applyOwner($args->getObject());
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $this->applyOwner($args->getObject());
    }

    private function applyOwner(object $entity): void
    {
        $user = null;

        if ($this->tokenStorage instanceof TokenStorageInterface && $this->tokenStorage->getToken() instanceof TokenInterface) {
            $user = $this->tokenStorage->getToken()->getUser();
        }

        if ($entity instanceof Ownable === false) {
            return;
        }

        if ($entity->getOwners()->isEmpty() && $user instanceof UserInterface) {
            $entity->addOwner($user);
        }
    }
}
