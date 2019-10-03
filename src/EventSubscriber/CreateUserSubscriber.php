<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\CreateUserEvent;
use ConnectHolland\UserBundle\UserBundleEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, RegistryInterface $registry)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->registry        = $registry;
    }

    public function onCreateUser(CreateUserEvent $event)
    {
        $user = $event->getUser();
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $event->getPlainPassword())
        );
        if ($user->isEnabled() === false) {
            $user->setPasswordRequestToken(bin2hex(random_bytes(32)));
        }

        /** @var EntityManagerInterface $userManager */
        $userManager = $this->registry->getManagerForClass(User::class);
        $userManager->persist($user);
        $userManager->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            UserBundleEvents::CREATE_USER => 'onCreateUser',
        ];
    }
}
