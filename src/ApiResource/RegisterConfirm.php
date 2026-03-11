<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\Response as OpenApiResponse;

/**
 * E-mail confirmation resource.
 *
 * Maps to RegistrationController::registrationConfirm via the .api route.
 * The email and token path parameters come from the confirmation link sent by e-mail.
 */
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/register/confirm/{email}/{token}',
            routeName: 'connectholland_user_registration_confirm.api',
            openapi: new OpenApiOperation(
                tags: ['Register'],
                summary: 'Confirm the e-mail address using the token from the confirmation link.',
                responses: [
                    '200' => new OpenApiResponse(description: 'E-mail address confirmed successfully.'),
                    '302' => new OpenApiResponse(description: 'Invalid or expired token — redirected to the registration page.'),
                ],
            ),
        ),
    ],
    paginationEnabled: false,
)]
class RegisterConfirm
{
    #[ApiProperty(description: 'The e-mail address to confirm.')]
    public string $email = '';

    #[ApiProperty(description: 'The confirmation token from the e-mail link.')]
    public string $token = '';
}
