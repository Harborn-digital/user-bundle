<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\EventSubscriber;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\AuthenticateUserEvent;
use ConnectHolland\UserBundle\EventSubscriber\AuthenticateUserSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Guard\AuthenticatorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\EventSubscriber\AuthenticateUserSubscriber
 */
class AuthenticateUserSubscriberTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::onAuthenticateUser
     */
    public function testOnAuthenticateUser()
    {
        $user  = new User();

        $token                = $this->createMock(TokenInterface::class);
        $authenticatorHandler = $this->createMock(GuardAuthenticatorHandler::class);
        $authenticator        = $this->createMock(AuthenticatorInterface::class);

        $request  = new Request();
        $response = new Response();
        $event    = new AuthenticateUserEvent($user, $request);

        $event->setResponse($response);

        $authenticator
            ->expects($this->once())
            ->method('createAuthenticatedToken')
            ->with($user, 'main')
            ->willReturn($token)
        ;

        $authenticatorHandler
            ->expects($this->once())
            ->method('handleAuthenticationSuccess')
            ->with($token, $request, $authenticator, 'main')
            ->willReturn($response)
        ;

        $authenticateUserSubscriber = new AuthenticateUserSubscriber($authenticatorHandler, $authenticator);
        $authenticateUserSubscriber->onAuthenticateUser($event);
    }
}
