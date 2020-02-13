<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\DeleteAccountEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Event\DeleteAccountEvent
 */
class DeleteAccountEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getUser
     */
    public function testGetUser()
    {
        $user     = new User();
        $password = 'password';
        $event    = new DeleteAccountEvent($user, $password);

        $this->assertEquals($user, $event->getUser());
    }

    /**
     * @covers ::__construct
     * @covers ::getResponse
     * @covers ::setResponse
     */
    public function testGetResponse()
    {
        $user     = new User();
        $response = new RedirectResponse('/');
        $event    = new DeleteAccountEvent($user);
        $event->setResponse($response);

        $this->assertEquals($response, $event->getResponse());
    }
}
