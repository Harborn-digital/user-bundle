<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\DependencyInjection\Compiler;

use ConnectHolland\UserBundle\DependencyInjection\Configuration;
use ConnectHolland\UserBundle\Routing\OAuthRouteLoader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ResourceOwnerMapsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition(OAuthRouteLoader::class) ) {
            $configuration = new Configuration();
            $config        = (new Processor())->processConfiguration($configuration, $container->getExtensionConfig(Configuration::CONFIG_ROOT_KEY));

            $resourceOwnerMaps = [];
            foreach (array_keys($config['firewalls']) as $firewall) {
                $resourceOwnerMaps[$firewall] = new Reference(sprintf('hwi_oauth.resource_ownermap.%s', $firewall));
            }

            $container->getDefinition(OAuthRouteLoader::class)->replaceArgument('$resourceOwnerMaps', $resourceOwnerMaps);
        }
    }
}
