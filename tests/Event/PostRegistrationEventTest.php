<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Event\PostRegistrationEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Event\PostRegistrationEvent
 */
class PostRegistrationEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getState
     * @covers ::getResponse
     * @covers ::getAction
     * @covers ::setResponse
     */
    public function testPostRegistrationEvent()
    {
        $state    = 'success';
        $response = new RedirectResponse('/home');
        $action   = 'register';
        $event    = new PostRegistrationEvent($state, $response, $action);

        $this->assertEquals($state, $event->getState());
        $this->assertEquals($response, $event->getResponse());
        $this->assertEquals($action, $event->getAction());

        $event->setResponse(new RedirectResponse('/'));

        $this->assertNotEquals($response, $event->getResponse());
    }
}
