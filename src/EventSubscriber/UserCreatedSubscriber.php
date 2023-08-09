<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\UserCreatedEventInterface;
use ConnectHolland\UserBundle\Mailer\RegistrationEmail;
use ConnectHolland\UserBundle\UserBundleEvents;

/**
 * @codeCoverageIgnore WIP
 */
final class UserCreatedSubscriber implements UserCreatedSubscriberInterface
{
    public function __construct(private RegistrationEmail $email)
    {
    }

    public function onUserCreated(UserCreatedEventInterface $event): void
    {
        $user = $event->getUser();
        if ($user->isEnabled() === false) {
            $this->email->send($user);
        }
    }

    /**
     * @codeCoverageIgnore No need to test this array 'config' method
     */
    public static function getSubscribedEvents()
    {
        return [
            UserBundleEvents::USER_CREATED => 'onUserCreated',
        ];
    }
}
