<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @codeCoverageIgnore WIP
 */
class OAuthRouteLoader extends Loader
{
    private $isLoaded = false;

    /**
     * @var array
     */
    private $resourceOwnerMaps;

    public function __construct(
        array $resourceOwnerMaps
    ) {
        $this->resourceOwnerMaps = $resourceOwnerMaps;
    }

    public function load($resource, $type = null): RouteCollection
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "connectholland_user_oauth" loader twice');
        }

        $routes = new RouteCollection();

        foreach ($this->resourceOwnerMaps as $firewallName => $resourceOwnerMap) {
            foreach (array_keys($resourceOwnerMap->getResourceOwners()) as $resourceName) {
                $this->addResourceRoute($routes, $firewallName, (string) $resourceName);
            }
        }

        $this->isLoaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'connectholland_user_oauth' === $type;
    }

    private function addResourceRoute(RouteCollection $routes, string $firewallName, string $resourceName): void
    {
        $routeName = sprintf(
            'connectholland_user_oauth_check_%s_%s',
            $firewallName,
            $resourceName
        );
        
        $path = sprintf('/login/oauth-check-%s', $resourceName);

        $routes->add($routeName, new Route($path));
    }
}
