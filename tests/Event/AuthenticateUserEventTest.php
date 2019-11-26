<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\AuthenticateUserEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Event\AuthenticateUserEvent
 */
class AuthenticateUserEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getUser
     * @covers ::getRequest
     */
    public function testAuthenticateUserEvent()
    {
        $user    = new User();
        $request = new Request();

        $event = new AuthenticateUserEvent($user, $request);

        $this->assertEquals($user, $event->getUser());
        $this->assertEquals($request, $event->getRequest());
    }

    /**
     * @covers ::setResponse
     * @covers ::getResponse
     */
    public function testAuthenticateUserEventResponse()
    {
        $response = new Response();

        $event = new AuthenticateUserEvent(new User(), new Request());
        $event->setResponse($response);

        $this->assertEquals($response, $event->getResponse());
    }
}
