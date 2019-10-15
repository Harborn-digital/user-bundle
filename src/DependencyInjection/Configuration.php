<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @codeCoverageIgnore WIP
 */
final class Configuration implements ConfigurationInterface
{
    public const CONFIG_ROOT_KEY = 'connectholland_user';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::CONFIG_ROOT_KEY);
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $rootNode = /** @scrutinizer ignore-deprecated */ $treeBuilder->root(self::CONFIG_ROOT_KEY); // Sf < 4.2 support

        }

        $rootNode
            ->children()
                ->arrayNode('oauth_firewalls')
                    ->prototype('scalar')->end()
                    ->defaultValue(['main'])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
