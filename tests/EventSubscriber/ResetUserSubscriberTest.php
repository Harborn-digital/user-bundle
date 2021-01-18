<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\EventSubscriber;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Event\ResetUserEvent;
use ConnectHolland\UserBundle\EventSubscriber\ResetUserSubscriber;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\EventSubscriber\ResetUserSubscriber
 */
class ResetUserSubscriberTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::onResetUser
     */
    public function testOnResetUser()
    {
        $event = new ResetUserEvent('example@example.com');

        $manager    = $this->createMock(ObjectManager::class);
        $user       = $this->createMock(UserInterface::class);
        $registry   = $this->createMock(ManagerRegistry::class);
        $repository = $this->createMock(ObjectRepository::class);

        $user
            ->expects($this->once())
            ->method('setPasswordRequestToken')
            ->willReturnSelf()
        ;

        $registry
            ->expects($this->once())
            ->method('getRepository')
            ->with(UserInterface::class)
            ->willReturn($repository)
        ;

        $registry
            ->expects($this->once())
            ->method('getManagerForClass')
            ->with(User::class)
            ->willReturn($manager)
        ;

        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with([
                'email' => 'example@example.com',
            ])
            ->willReturn($user)
        ;

        $manager
            ->expects($this->once())
            ->method('persist')
            ->with($user)
        ;

        $manager
            ->expects($this->once())
            ->method('flush')
        ;

        $authenticateUserSubscriber = new ResetUserSubscriber($registry);
        $authenticateUserSubscriber->onResetUser($event);
    }
}
