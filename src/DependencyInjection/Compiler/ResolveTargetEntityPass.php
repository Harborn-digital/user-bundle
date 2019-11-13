<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\DependencyInjection\Compiler;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * @codeCoverageIgnore WIP
 */
final class ResolveTargetEntityPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        try {
            $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');
            $userClass                   = $container->getParameter('connectholland_user.user_class');
        } catch (InvalidArgumentException $exception) {
            return;
        }

        $resolveTargetEntityListener->addMethodCall(
            'addResolveTargetEntity',
            [UserInterface::class, $userClass, []]
        );
    }
}
