<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\UserCreatedEvent;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Event\UserCreatedEvent
 */
class UserCreatedEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getUser
     */
    public function testGetUser()
    {
        $user  = new User();
        $event = new UserCreatedEvent($user);

        $this->assertEquals($user, $event->getUser());
    }
}
