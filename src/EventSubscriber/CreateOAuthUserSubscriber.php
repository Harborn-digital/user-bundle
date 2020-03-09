<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\CreateOAuthUserEventInterface;
use ConnectHolland\UserBundle\UserBundleEvents;

final class CreateOAuthUserSubscriber implements CreateOAuthUserSubscriberInterface
{
    public function onCreateOAuthUser(CreateOAuthUserEventInterface $event): void
    {
        $user = $event->getUser();
        $user->setEnabled(true);
        if ($event->getResponse()->getEmail()) {
            $user->setEmail($event->getResponse()->getEmail());
        }
    }

    /**
     * @codeCoverageIgnore No need to test this array 'config' method
     */
    public static function getSubscribedEvents()
    {
        return [
            UserBundleEvents::CREATE_OAUTH_USER => 'onCreateOAuthUser',
        ];
    }
}
