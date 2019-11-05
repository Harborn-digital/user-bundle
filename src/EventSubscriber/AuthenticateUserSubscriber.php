<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\AuthenticateUserEventInterface;
use ConnectHolland\UserBundle\Security\UserBundleAuthenticator;
use ConnectHolland\UserBundle\UserBundleEvents;
use Symfony\Component\Security\Guard\AuthenticatorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

final class AuthenticateUserSubscriber implements AuthenticateUserSubscriberInterface
{
    /**
     * @var GuardAuthenticatorHandler
     */
    private $guardAuthenticatorHandler;

    /**
     * @var AuthenticatorInterface
     */
    private $authenticator;

    public function __construct(GuardAuthenticatorHandler $guardAuthenticatorHandler, AuthenticatorInterface $authenticator)
    {
        $this->guardAuthenticatorHandler = $guardAuthenticatorHandler;
        $this->authenticator             = $authenticator;
    }

    public function onAuthenticateUser(AuthenticateUserEventInterface $event): void
    {
        $providerKey = 'main'; // TODO: Make configurable or read from request

        $token = $this->authenticator->createAuthenticatedToken($event->getUser(), $providerKey);

        $this->guardAuthenticatorHandler->authenticateWithToken($token, $event->getRequest(), $providerKey);

        $event->setResponse($this->guardAuthenticatorHandler->handleAuthenticationSuccess($token, $event->getRequest(), $this->authenticator, $providerKey));
    }

    /**
     * @codeCoverageIgnore No need to test this array 'config' method
     */
    public static function getSubscribedEvents()
    {
        return [
            UserBundleEvents::AUTHENTICATE_USER => 'onAuthenticateUser',
        ];
    }
}
