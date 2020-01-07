<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\EventSubscriber;

use ConnectHolland\UserBundle\Event\PostRegistrationEvent;
use ConnectHolland\UserBundle\EventSubscriber\FlashSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\EventSubscriber\FlashSubscriber
 */
class FlashSubscriberTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::addFlashMessage
     * @covers ::translate
     */
    public function testAddFlashMessage()
    {
        $state    = 'success';
        $response = new RedirectResponse('/');
        $action   = 'register';
        $event    = new PostRegistrationEvent($state, $response, $action);

        $session    = $this->createMock(Session::class);
        $translator = $this->createMock(TranslatorInterface::class);
        $flashBag   = $this->createMock(FlashBagInterface::class);

        $translator
            ->expects($this->once())
            ->method('trans')
            ->with('connectholland_user.flash_message.register.flash.success')
            ->willReturn('Check your e-mail to complete your registration.');

        $session
            ->expects($this->once())
            ->method('getFlashBag')
            ->willReturn($flashBag);

        $flashBag
            ->expects($this->once())
            ->method('add');

        $flashSubscriber = new FlashSubscriber($session, $translator);
        $flashSubscriber->addFlashMessage($event);
    }
}
