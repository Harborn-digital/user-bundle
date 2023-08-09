<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Event\UsernameUpdatedEvent;
use ConnectHolland\UserBundle\Mailer\ValidateUsernameEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UsernameUpdatedSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly ValidateUsernameEmail $email, private $tokenStorage = null)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            UsernameUpdatedEvent::class => 'createUsernameUpdateRequest',
        ];
    }

    public function createUsernameUpdateRequest(UsernameUpdatedEvent $event): void
    {
        /** @var UserInterface $user */
        $user = $event->getSubject();
        if ($user->isEnabled() === false) {
            $this->email->send($user);
        }

        if ($this->tokenStorage !== null) {
            $this->tokenStorage->setToken(null);
        }

        if ($event->hasArgument('request')) {
            $request = $event->getArgument('request');
            if ($request instanceof Request) {
                $request->getSession()->invalidate();
            }
        }
    }
}
