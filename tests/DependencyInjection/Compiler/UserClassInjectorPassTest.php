<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\DependencyInjection\Compiler;

use ConnectHolland\UserBundle\DependencyInjection\Compiler\UserClassInjectorPass;
use ConnectHolland\UserBundle\DependencyInjection\Configuration;
use ConnectHolland\UserBundle\Entity\BaseUser;
use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\DependencyInjection\Compiler\UserClassInjectorPass
 */
final class UserClassInjectorPassTest extends TestCase
{
    /**
     * @covers ::process
     */
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container
            ->register(UserRepository::class)
            ->setArgument('$class', '')
            ->setPublic(false)
        ;

        $this->process($container);

        $this->assertTrue($container->hasDefinition(UserRepository::class));
        $this->assertEquals(User::class, $container->getDefinition(UserRepository::class)->getArgument('$class'));
    }

    /**
     * @covers ::process
     */
    public function testProcessForUserExtend()
    {
        $container = new ContainerBuilder();
        $container
            ->register(UserRepository::class)
            ->setArgument('$class', '')
            ->setPublic(false)
        ;

        $user = new class() extends BaseUser {
            private $id;

            public function getId(): ?int
            {
                return $this->id;
            }
        };

        $container->prependExtensionConfig(
            Configuration::CONFIG_ROOT_KEY,
            [
                'user_class' => get_class($user),
            ]
        );

        $this->process($container);

        $this->assertTrue($container->hasDefinition(UserRepository::class));
        $this->assertEquals(get_class($user), $container->getDefinition(UserRepository::class)->getArgument('$class'));
    }

    protected function process(ContainerBuilder $container)
    {
        (new UserClassInjectorPass())->process($container);
    }
}
