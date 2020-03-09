<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\CreateOAuthUserEvent;
use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Event\CreateOAuthUserEvent
 */
class CreateOAuthUserEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getUser
     * @covers ::getResponse
     */
    public function testGetUserAndResponse()
    {
        $response = new PathUserResponse();
        $event    = new CreateOAuthUserEvent(User::class, $response);

        $this->assertInstanceOf(User::class, $event->getUser());
        $this->assertEquals($response, $event->getResponse());
    }

    /**
     * @covers ::__construct
     */
    public function testInvalidArgumentExceptionOnWrongInterface()
    {
        $this->expectException(\InvalidArgumentException::class);
        new CreateOAuthUserEvent(\stdClass::class, new PathUserResponse());
    }
}
