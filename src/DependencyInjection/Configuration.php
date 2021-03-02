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
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->beforeNormalization()
                ->ifTrue(function ($v) { return !isset($v['password_requirements']); })
                ->then(function ($v) {
                    $v['password_requirements'] = [];

                    return $v;
                })
            ->end()
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
                ->arrayNode('password_requirements')
                    ->children()
                        ->integerNode('min_strength')
                            ->defaultValue(4)
                        ->end()
                        ->integerNode('min_length')
                            ->defaultValue(8)
                        ->end()
                        ->booleanNode('not_pwned')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
