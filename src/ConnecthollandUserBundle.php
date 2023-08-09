<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle;

use ConnectHolland\UserBundle\DependencyInjection\Compiler\PasswordRequirementsInjectorPass;
use ConnectHolland\UserBundle\DependencyInjection\Compiler\ResourceOwnerMapsPass;
use ConnectHolland\UserBundle\DependencyInjection\Compiler\UserClassInjectorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ConnecthollandUserBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ResourceOwnerMapsPass());
        $container->addCompilerPass(new UserClassInjectorPass());
        $container->addCompilerPass(new PasswordRequirementsInjectorPass());
    }
}
