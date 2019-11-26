<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\DependencyInjection\Compiler;

use ConnectHolland\UserBundle\DependencyInjection\Configuration;
use ConnectHolland\UserBundle\Security\PasswordConstraints;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PasswordRequirementsInjectorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config        = (new Processor())->processConfiguration($configuration, $container->getExtensionConfig(Configuration::CONFIG_ROOT_KEY));

        $definition = $container->getDefinition(PasswordConstraints::class);
        $definition->setArgument('$passwordRequirements', $config['password_requirements']);
    }
}
