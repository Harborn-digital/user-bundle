<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\UserCreatedEvent;
use ConnectHolland\UserBundle\UserBundleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\UriSigner;

class UserCreatedSubscriber implements EventSubscriberInterface
{
    /**
     * @var UriSigner
     */
    private $uriSigner;

    public function __construct(UriSigner $uriSigner)
    {
        $this->uriSigner = $uriSigner;
    }

    public function onUserCreated(UserCreatedEvent $event)
    {
        $user = $event->getUser();

        echo sprintf('Send an email with a signed uri to %s', $user->getUsername());
    }

    public static function getSubscribedEvents()
    {
        return [
            UserBundleEvents::USER_CREATED => 'onUserCreated',
        ];
    }
}
