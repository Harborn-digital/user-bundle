<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\DependencyInjection;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Entity\UserInterface;
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
            $rootNode = /* @scrutinizer ignore-deprecated */ $treeBuilder->root(self::CONFIG_ROOT_KEY); // Sf < 4.2 support
        }

        $rootNode
            ->/* @scrutinizer ignore-call */addDefaultsIfNotSet()
            ->children()
                ->arrayNode('firewalls')
                    ->useAttributeAsKey('name')
                    ->defaultValue(['main' => ['prefix' => '/']])
                    ->prototype('array')
                        ->children()
                            ->scalarNode('prefix')
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('user_class')
                    ->defaultValue(User::class)
                    ->validate()
                        ->ifTrue(function ($value) {
                            return array_search(UserInterface::class, class_implements($value)) === false;
                        })
                        ->thenInvalid(sprintf('The class should implement %s', UserInterface::class))
                    ->end()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
