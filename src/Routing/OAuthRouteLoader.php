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
            foreach ($resourceOwnerMap->getResourceOwners() as $resourceName => $path) {
                $this->addResourceRoute($routes, $firewallName, $resourceName, (string) $path);
            }
        }

        $this->isLoaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'connectholland_user_oauth' === $type;
    }

    protected function addResourceRoute(RouteCollection $routes, string $firewallName, string $resourceName, string $path): void
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
