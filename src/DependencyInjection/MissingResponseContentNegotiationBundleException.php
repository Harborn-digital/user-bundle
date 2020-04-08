<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\DependencyInjection;

class MissingResponseContentNegotiationBundleException extends \RuntimeException
{
    protected $message = 'The response content negotiation bundle is not enabled. Add \'GisoStallenberg\Bundle\ResponseContentNegotiationBundle\GisoStallenbergResponseContentNegotiationBundle\' to the enabled bundles.';
}
