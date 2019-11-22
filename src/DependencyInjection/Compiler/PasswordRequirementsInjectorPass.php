<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\DependencyInjection\Compiler;

use ConnectHolland\UserBundle\DependencyInjection\Configuration;
use ConnectHolland\UserBundle\Form\PasswordType;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PasswordRequirementsInjectorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $config = $container->getExtensionConfig(Configuration::CONFIG_ROOT_KEY);

        $definition = $container->getDefinition(PasswordType::class);
        $definition->setArgument('$passwordRequirements', $config[0]['password_requirements']);
    }
}
