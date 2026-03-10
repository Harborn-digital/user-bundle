<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber\Doctrine;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Security\Ownable;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OwnableSubscriber implements EventSubscriber
{
    public function __construct(private readonly ?TokenStorageInterface $tokenStorage = null)
    {
    }

    /**
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $user   = null;

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

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->preUpdate($args);
    }
}
