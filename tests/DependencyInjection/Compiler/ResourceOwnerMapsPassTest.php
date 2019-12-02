<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\DependencyInjection\Compiler\PasswordRequirementsInjectorPassTest;

use ConnectHolland\UserBundle\DependencyInjection\Compiler\ResourceOwnerMapsPass;
use ConnectHolland\UserBundle\Routing\OAuthRouteLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\DependencyInjection\Compiler\ResourceOwnerMapsPass
 */
final class ResourceOwnerMapsPassTest extends TestCase
{
    /**
     * @covers ::process
     */
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container
            ->register(OAuthRouteLoader::class)
            ->setArgument('$resourceOwnerMaps', [])
            ->setPublic(false)
        ;

        $this->process($container);

        $this->assertTrue($container->hasDefinition(OAuthRouteLoader::class));
        $this->assertArrayHasKey('main', $container->getDefinition(OAuthRouteLoader::class)->getArgument('$resourceOwnerMaps'));
        $this->assertInstanceOf(Reference::class, $container->getDefinition(OAuthRouteLoader::class)->getArgument('$resourceOwnerMaps')['main']);
        $this->assertEquals('hwi_oauth.resource_ownermap.main', (string) $container->getDefinition(OAuthRouteLoader::class)->getArgument('$resourceOwnerMaps')['main']);
    }

    protected function process(ContainerBuilder $container)
    {
        (new ResourceOwnerMapsPass())->process($container);
    }
}
