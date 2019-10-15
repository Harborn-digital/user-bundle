<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\CreateUserEvent;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Event\CreateUserEvent
 */
class CreateUserEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getUser
     * @covers ::getPlainPassword
     */
    public function testGetUser()
    {
        $user     = new User();
        $password = 'password';
        $event    = new CreateUserEvent($user, $password);

        $this->assertEquals($user, $event->getUser());
        $this->assertEquals($password, $event->getPlainPassword());
    }
}
