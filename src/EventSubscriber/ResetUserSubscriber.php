<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Event\ResetUserEventInterface;
use ConnectHolland\UserBundle\UserBundleEvents;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

final class ResetUserSubscriber implements ResetUserSubscriberInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function onResetUser(ResetUserEventInterface $event): void
    {
        $user = $this->registry->getRepository(UserInterface::class)->findOneBy([
            'email' => $event->getEmail(),
        ]);

        if ($user instanceof UserInterface) {
            $user->setPasswordRequestToken(bin2hex(random_bytes(32))); // Todo: make time based

            /** @var EntityManagerInterface $userManager */
            $userManager = $this->registry->getManagerForClass(User::class);
            $userManager->persist($user);
            $userManager->flush();
        }
    }

    /**
     * @codeCoverageIgnore No need to test this array 'config' method
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserBundleEvents::RESET_USER => 'onResetUser',
        ];
    }
}
