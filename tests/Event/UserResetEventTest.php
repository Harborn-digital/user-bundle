<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Event\UserResetEvent;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Event\UserResetEvent
 */
class UserResetEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getEmail
     */
    public function testGetEmail()
    {
        $email = 'example@example.com';
        $event = new UserResetEvent($email);

        $this->assertEquals($email, $event->getEmail());
    }
}
