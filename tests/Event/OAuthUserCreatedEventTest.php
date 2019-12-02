<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\OAuthUserCreatedEvent;
use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Event\OAuthUserCreatedEvent
 */
class OAuthUserCreatedEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getUser
     * @covers ::getResponse
     */
    public function testGetUserAndResponse()
    {
        $user     = new User();
        $response = new PathUserResponse();
        $event    = new OAuthUserCreatedEvent($user, $response);

        $this->assertEquals($user, $event->getUser());
        $this->assertEquals($response, $event->getResponse());
    }
}
