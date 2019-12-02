<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\DependencyInjection\Compiler\PasswordRequirementsInjectorPassTest;

use ConnectHolland\UserBundle\DependencyInjection\Compiler\ResolveTargetEntityPass;
use ConnectHolland\UserBundle\DependencyInjection\Configuration;
use ConnectHolland\UserBundle\Entity\BaseUser;
use ConnectHolland\UserBundle\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\DependencyInjection\Compiler\ResolveTargetEntityPass
 */
final class ResolveTargetEntityPassTest extends TestCase
{
    /**
     * @covers ::process
     */
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container
            ->register('doctrine.orm.listeners.resolve_target_entity')
        ;

        $this->process($container);

        $this->assertEquals(User::class, $container->getDefinition('doctrine.orm.listeners.resolve_target_entity')->getMethodCalls()[0][1][1]);
    }

    /**
     * @covers ::process
     */
    public function testProcessForUserExtend()
    {
        $container = new ContainerBuilder();
        $container
            ->register('doctrine.orm.listeners.resolve_target_entity')
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

        $this->assertEquals(get_class($user), $container->getDefinition('doctrine.orm.listeners.resolve_target_entity')->getMethodCalls()[0][1][1]);
    }

    protected function process(ContainerBuilder $container)
    {
        (new ResolveTargetEntityPass())->process($container);
    }
}
