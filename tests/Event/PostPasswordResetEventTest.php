<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Event\PostPasswordResetEvent;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Event\PostPasswordResetEvent
 */
class PostPasswordResetEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getState
     * @covers ::getAction
     */
    public function testPostPasswordResetEvent()
    {
        $state    = 'notice';
        $action   = 'reset';
        $event    = new PostPasswordResetEvent($state, $action);

        $this->assertEquals($state, $event->getState());
        $this->assertEquals($action, $event->getAction());
    }
}
