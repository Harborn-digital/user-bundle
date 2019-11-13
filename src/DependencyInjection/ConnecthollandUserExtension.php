<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\DependencyInjection;

use HaydenPierce\ClassFinder\ClassFinder;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\AbstractResourceOwner;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GenericOAuth2ResourceOwner;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @codeCoverageIgnore WIP
 */
class ConnecthollandUserExtension extends Extension implements ExtensionInterface, PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);
        $container->setParameter('connectholland_user.user_class', $config['user_class']);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $config = $this->getResourceOwnersConfiguration($container);
        $container->prependExtensionConfig('hwi_oauth', $config);
    }

    private function createConfigForResourceOwners($resourceOwners): array
    {
        $configForResourceOwners = [];
        foreach ($resourceOwners as $resourceOwner => $resourceOwnerConfig) {
            $options = json_decode($resourceOwnerConfig['options'] ?? '{}', true);

            $name   = $options['name'] ?? $resourceOwner;
            $id     = $resourceOwnerConfig['id'] ?? $options['id'] ?? '';
            $secret = $resourceOwnerConfig['secret'] ?? $options['secret'] ?? '';
            $scope  = $resourceOwnerConfig['scope'] ?? $options['scope'] ?? '';

            foreach (['name', 'id', 'secret', 'scope'] as $key) {
                if (isset($options[$key])) {
                    unset($options[$key]);
                }
            }

            $configForResourceOwners[$name] = [
                'type'          => $resourceOwner,
                'client_id'     => $id,
                'client_secret' => $secret,
                'scope'         => trim($scope, "'"),
                'options'       => $options,
            ];
        }

        return $configForResourceOwners;
    }

    private function getResourceOwnersConfiguration(ContainerBuilder $container): array
    {
        $resourceOwners = $this->getResourceOwnersByClasses();
        $resourceOwners = $this->fillResourceOwnersWithEnvVars($container, $resourceOwners);

        $configuration   = new Configuration();
        $processedConfig = (new Processor())->processConfiguration($configuration, $container->getExtensionConfig(Configuration::CONFIG_ROOT_KEY));

        $config = [
            'firewall_name'   => $processedConfig['oauth_firewalls'],
            'resource_owners' => $this->createConfigForResourceOwners(array_filter($resourceOwners)),
        ];

        return $config;
    }

    private function getResourceOwnersByClasses(): array
    {
        $resourceOwners = [];
        $classes        = ClassFinder::getClassesInNamespace((new \ReflectionClass(AbstractResourceOwner::class))->getNamespaceName());
        foreach ($classes as $class) {
            if (is_subclass_of($class, GenericOAuth2ResourceOwner::class)) {
                $naming                                 = explode('ResourceOwner', (new \ReflectionClass($class))->getShortName());
                $resourceOwners[strtolower($naming[0])] = [];
            }
        }

        return $resourceOwners;
    }

    private function fillResourceOwnersWithEnvVars(ContainerBuilder $container, array $resourceOwners): array
    {
        $types = [
            'id',
            'secret',
            'scope',
            'options',
        ];

        foreach ($resourceOwners as $resourceOwner => $options) {
            foreach ($types as $type) {
                $envVarName = sprintf('USERBUNDLE_OAUTH_%s_%s', strtoupper($resourceOwner), strtoupper($type));

                if (getenv($envVarName) !== false || isset($_ENV[$envVarName]) !== false) {
                    $parameterName                         = sprintf('env(%s)', $envVarName);
                    $resourceOwners[$resourceOwner][$type] = $container->resolveEnvPlaceholders($container->getParameter($parameterName), true);
                }
            }
        }

        return $resourceOwners;
    }
}
