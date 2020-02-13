<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\EventSubscriber;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\DeleteAccountEvent;
use ConnectHolland\UserBundle\EventSubscriber\DeleteAccountSubscriber;
use ConnectHolland\UserBundle\Mailer\DeleteEmail;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\EventSubscriber\DeleteAccountSubscriber
 */
class DeleteAccountSubscriberTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::onDeleteAccountEvent
     * @covers ::sendMail
     */
    public function testOnDeleteAccountEvent(): void
    {
        $deleteEmail = $this->createMock(DeleteEmail::class);
        $user        = new User();
        $router      = $this->createMock(UrlGeneratorInterface::class);
        $event       = new DeleteAccountEvent($user);

        $deleteEmail
            ->expects($this->once())
            ->method('send')
            ->with($user);

        $router
            ->expects($this->once())
            ->method('generate')
            ->willReturn('inloggen');

        $deleteAccountSubscriber = new DeleteAccountSubscriber($deleteEmail, $router);
        $deleteAccountSubscriber->onDeleteAccountEvent($event);
    }
}
