<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\DependencyInjection\Compiler;

use ConnectHolland\UserBundle\DependencyInjection\Compiler\PasswordRequirementsInjectorPass;
use ConnectHolland\UserBundle\DependencyInjection\Configuration;
use ConnectHolland\UserBundle\Security\PasswordConstraints;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\DependencyInjection\Compiler\PasswordRequirementsInjectorPass
 */
final class PasswordRequirementsInjectorPassTest extends TestCase
{
    /**
     * @covers ::process
     */
    public function testProcess()
    {
        $requirements = [
            'min_strength' => 4,
            'min_length'   => 8,
            'not_pwned'    => true,
        ];

        $container = new ContainerBuilder();
        $container
            ->register(PasswordConstraints::class)
            ->setPublic(false)
        ;

        $container->prependExtensionConfig(
            Configuration::CONFIG_ROOT_KEY,
            [
                'password_requirements' => $requirements,
            ]
        );

        $this->process($container);

        $this->assertTrue($container->hasDefinition(PasswordConstraints::class));
        $this->assertEquals($requirements, $container->getDefinition(PasswordConstraints::class)->getArgument('$passwordRequirements'));
    }

    protected function process(ContainerBuilder $container)
    {
        (new PasswordRequirementsInjectorPass())->process($container);
    }
}
