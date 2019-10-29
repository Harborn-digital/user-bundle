<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Event\UserResetEvent;
use ConnectHolland\UserBundle\Mailer\ResetEmail;
use ConnectHolland\UserBundle\UserBundleEvents;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @codeCoverageIgnore WIP
 */
final class UserResetSubscriber implements EventSubscriberInterface
{
    /**
     * @var ResetEmail
     */
    private $email;

    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(ResetEmail $email, RegistryInterface $registry)
    {
        $this->email    = $email;
        $this->registry = $registry;
    }

    public function onUserReset(UserResetEvent $event): void
    {
        $user = $this->registry->getRepository(User::class)->findOneBy([
            'email' => $event->getEmail(),
        ]);

        if ($user instanceof UserInterface) {
            $this->email->send($user);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserBundleEvents::USER_RESET => 'onUserReset',
        ];
    }
}
