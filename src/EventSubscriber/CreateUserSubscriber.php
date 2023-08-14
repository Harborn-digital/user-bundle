<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\CreateUserEventInterface;
use ConnectHolland\UserBundle\UserBundleEvents;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @codeCoverageIgnore WIP
 */
final class CreateUserSubscriber implements CreateUserSubscriberInterface
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(UserPasswordHasherInterface $passwordHasher, ManagerRegistry $registry)
    {
        $this->passwordHasher = $passwordHasher;
        $this->registry        = $registry;
    }

    public function onCreateUser(CreateUserEventInterface $event): void
    {
        $user = $event->getUser();
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $event->getPlainPassword())
        );
        if ($user->isEnabled() === false) {
            $user->setPasswordRequestToken(bin2hex(random_bytes(32)));
        }

        /** @var EntityManagerInterface $userManager */
        $userManager = $this->registry->getManagerForClass(User::class);
        $userManager->persist($user);
        $userManager->flush();
    }

    /**
     * @codeCoverageIgnore No need to test this array 'config' method
     */
    public static function getSubscribedEvents()
    {
        return [
            UserBundleEvents::CREATE_USER => 'onCreateUser',
        ];
    }
}
