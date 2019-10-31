<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Event\UserResetEvent;
use ConnectHolland\UserBundle\EventSubscriber\UserResetSubscriber;
use ConnectHolland\UserBundle\Mailer\ResetEmailInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\EventSubscriber\UserResetSubscriber
 */
class UserResetSubscriberTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::onUserReset
     */
    public function testOnUserReset()
    {
        $registry   = $this->createMock(RegistryInterface::class);
        $repository = $this->createMock(ObjectRepository::class);
        $email      = $this->createMock(ResetEmailInterface::class);

        $user      = new User();
        $event     = new UserResetEvent('example@example.com');

        $registry
            ->expects($this->once())
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($repository)
        ;

        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with([
                'email' => 'example@example.com',
            ])
            ->willReturn($user)
        ;

        $email
            ->expects($this->once())
            ->method('send')
            ->with($user)
        ;

        $authenticateUserSubscriber = new UserResetSubscriber($email, $registry);
        $authenticateUserSubscriber->onUserReset($event);
    }
}
