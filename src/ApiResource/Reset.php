<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\Response as OpenApiResponse;

/**
 * Password reset request resource.
 *
 * Maps to ResetController::reset via the .api route.
 * Triggers a password-reset e-mail to the given address.
 *
 * NOTE: The old Swagger 2.0 docs incorrectly labelled this field "username".
 * ResetType uses an EmailType field named "email".
 */
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/account/password-reset',
            status: 200,
            routeName: 'connectholland_user_reset.api',
            output: false,
            openapi: new OpenApiOperation(
                tags: ['Account'],
                summary: 'Request a password reset e-mail.',
                responses: [
                    '200' => new OpenApiResponse(description: 'Password reset e-mail sent successfully.'),
                    '400' => new OpenApiResponse(description: 'Invalid e-mail address.'),
                ],
            ),
        ),
    ],
    paginationEnabled: false,
)]
class Reset
{
    #[ApiProperty(description: 'The e-mail address of the account to reset.')]
    public string $email = '';
}
