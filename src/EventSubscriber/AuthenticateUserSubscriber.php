<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\AuthenticateUserEventInterface;
use ConnectHolland\UserBundle\UserBundleEvents;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

final class AuthenticateUserSubscriber implements AuthenticateUserSubscriberInterface
{
    public function __construct(
        private readonly UserAuthenticatorInterface $userAuthenticator,
        private readonly AuthenticatorInterface $authenticator,
    ) {}

    public function onAuthenticateUser(AuthenticateUserEventInterface $event): void
    {
        $response = $this->userAuthenticator->authenticateUser(
            $event->getUser(),
            $this->authenticator,
            $event->getRequest()
        );

        $event->setResponse($response);
    }

    /**
     * @codeCoverageIgnore No need to test this array 'config' method
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserBundleEvents::AUTHENTICATE_USER => 'onAuthenticateUser',
        ];
    }
}
