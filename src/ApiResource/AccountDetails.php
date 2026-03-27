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
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\Response as OpenApiResponse;
use ArrayObject;

/**
 * Account details resource.
 *
 * Maps to AccountController::edit via the .api route.
 *
 * GET  — returns the currently authenticated user's account data (email).
 * POST — updates email and/or password. The password field uses Symfony's
 *        RepeatedType, so it is sent as {"first": "...", "second": "..."}.
 *        Omitting plainPassword (or sending null) leaves the password unchanged.
 *
 * Both operations require full authentication (IS_AUTHENTICATED_FULLY).
 */
#[ApiResource(
    operations: [
        new Get(
            name: 'account_details_get',
            uriTemplate: '/account/details',
            routeName: 'connectholland_user_account_account.api',
            openapi: new OpenApiOperation(
                tags: ['Account'],
                summary: 'Return the authenticated user account info.',
                responses: [
                    '200' => new OpenApiResponse(
                        description: 'The user account.',
                        content: new ArrayObject([
                            'application/json' => [
                                'schema' => ['$ref' => '#/components/schemas/AccountDetails'],
                            ],
                        ]),
                    ),
                    '401' => new OpenApiResponse(description: 'Not authenticated.'),
                ],
                security: [['apiKey' => []]],
            ),
            normalizationContext: ['groups' => ['account']],
        ),
        new Post(
            uriTemplate: '/account/details',
            status: 200,
            routeName: 'connectholland_user_account_account.api',
            output: false,
            openapi: new OpenApiOperation(
                tags: ['Account'],
                summary: 'Update the authenticated user email and/or password.',
                responses: [
                    '200' => new OpenApiResponse(description: 'Account updated successfully.'),
                    '400' => new OpenApiResponse(description: 'Validation failed.'),
                    '401' => new OpenApiResponse(description: 'Not authenticated.'),
                ],
                security: [['apiKey' => []]],
            ),
        ),
    ],
    paginationEnabled: false,
)]
class AccountDetails
{
    #[ApiProperty(description: 'The account e-mail address.')]
    public string $email = '';

    /**
     * New password. Uses Symfony RepeatedType: send {"first": "pw", "second": "pw"}.
     * Omit or send null to leave the current password unchanged.
     */
    #[ApiProperty(description: 'New password (repeated). Omit to leave the current password unchanged.')]
    public ?PlainPassword $plainPassword = null;
}
