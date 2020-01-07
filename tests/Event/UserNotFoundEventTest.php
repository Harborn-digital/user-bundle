<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Event\UserNotFoundEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Event\UserNotFoundEvent
 */
class UserNotFoundEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getResponse
     * @covers ::getState
     * @covers ::getAction
     * @covers ::setResponse
     */
    public function testUserNotFoundEvent()
    {
        $response = new RedirectResponse('/login');
        $state    = 'danger';
        $action   = 'registrationConfirm';
        $event    = new UserNotFoundEvent($response, $state, $action);

        $this->assertEquals($response, $event->getResponse());
        $this->assertEquals($state, $event->getState());
        $this->assertEquals($action, $event->getAction());

        $event->setResponse(new RedirectResponse('/'));

        $this->assertNotEquals($response, $event->getResponse());
    }
}
