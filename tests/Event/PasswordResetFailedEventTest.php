<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Event\PasswordResetFailedEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Event\PasswordResetFailedEvent
 */
class PasswordResetFailedEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getResponse
     * @covers ::getState
     * @covers ::getAction
     * @covers ::setResponse
     */
    public function testPasswordResetFailedEvent()
    {
        $response = new RedirectResponse('/login');
        $state    = 'danger';
        $action   = 'resetPassword';
        $event    = new PasswordResetFailedEvent($response, $state, $action);

        $this->assertEquals($response, $event->getResponse());
        $this->assertEquals($state, $event->getState());
        $this->assertEquals($action, $event->getAction());

        $event->setResponse(new RedirectResponse('/'));

        $this->assertNotEquals($response, $event->getResponse());
    }
}
