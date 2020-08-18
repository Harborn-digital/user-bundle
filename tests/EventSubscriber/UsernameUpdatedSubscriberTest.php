<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\EventSubscriber;

use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Event\UsernameUpdatedEvent;
use ConnectHolland\UserBundle\EventSubscriber\UsernameUpdatedSubscriber;
use ConnectHolland\UserBundle\Mailer\MailerInterface;
use ConnectHolland\UserBundle\Mailer\ValidateUsernameEmail;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Negotiation\NegotiatorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Test the username updated subscriber.
 *
 * @coversDefaultClass \ConnectHolland\UserBundle\EventSubscriber\UsernameUpdatedSubscriber
 */
class UsernameUpdatedSubscriberTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testCanBeInstantiaded(): void
    {
        [$subject] = $this->getTestClass();
        $this->assertInstanceOf(UsernameUpdatedSubscriber::class, $subject, 'Asserting that the class can be instantiated.');
    }

    /**
     * @covers ::createUsernameUpdateRequest
     */
    public function testWhenTheUsernameChangesTheUserHasToRevalidateTheUsername(): void
    {
        [$subject, $email, $tokenStorage, $mailer, $router, $uriSigner] = $this->getTestClass();
        $user                                                           = $this->createMock(UserInterface::class);
        $event                                                          = new UsernameUpdatedEvent($user);
        $request                                                        = $this->createMock(Request::class);
        $session                                                        = $this->createMock(SessionInterface::class);

        $user
            ->expects($this->once())
            ->method('isEnabled')
            ->willReturn(false)
        ;

        $request
            ->expects($this->once())
            ->method('getSession')
            ->willReturn($session)
        ;

        $mailer
            ->expects($this->once())
            ->method('createMessageAndSend')
        ;

        $tokenStorage
            ->expects($this->once())
            ->method('setToken')
            ->with(null)
        ;
        $session
            ->expects($this->once())
            ->method('invalidate')
        ;

        $event->setArgument('request', $request);

        $subject->createUsernameUpdateRequest($event);
    }

    /**
     * @covers ::getSubscribedEvents
     */
    public function testUsernameUpdatedSubscriberSubscribesToTheUsernameUpdatedEvent(): void
    {
        [$subject] = $this->getTestClass();
        $this->assertArrayHasKey(UsernameUpdatedEvent::class, $subject->getSubscribedEvents(), 'Asserting that the subscriber subscribes to the UsernameUpdatedEvent.');
    }

    public function getTestClass(): array
    {
        $mailer       = $this->createMock(MailerInterface::class);
        $router       = $this->createMock(RouterInterface::class);
        $uriSigner    = $this->createMock(UriSigner::class);
        $negotiator   = $this->createMock(NegotiatorInterface::class);
        $stack        = $this->createMock(RequestStack::class);
        $email        = new ValidateUsernameEmail($router, $uriSigner, $negotiator, $stack);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);

        $email->setMailer($mailer);

        return [new UsernameUpdatedSubscriber($email, $tokenStorage), $email, $tokenStorage, $mailer, $router, $uriSigner];
    }
}
