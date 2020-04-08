<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\EventSubscriber;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Event\CreateOAuthUserEvent;
use ConnectHolland\UserBundle\EventSubscriber\CreateOAuthUserSubscriber;
use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\EventSubscriber\CreateOAuthUserSubscriber
 */
class CreateOAuthUserSubscriberTest extends TestCase
{
    /**
     * @covers ::onCreateOAuthUser
     */
    public function testOnResetUser()
    {
        $response = new PathUserResponse();
        $response->setPaths(['email' => 'email']);
        $response->setData(json_encode(['email' => 'example@example.com']));

        $event = new CreateOAuthUserEvent(User::class, $response);

        $authenticateUserSubscriber = new CreateOAuthUserSubscriber();
        $authenticateUserSubscriber->onCreateOAuthUser($event);

        $this->assertInstanceOf(UserInterface::class, $event->getUser());
        $this->assertTrue($event->getUser()->isEnabled());
        $this->assertEquals('example@example.com', $event->getUser()->getEmail());
    }
}
