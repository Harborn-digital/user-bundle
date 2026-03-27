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
 * User registration resource.
 *
 * Maps to RegistrationController::register via the .api route.
 * On success returns a JWT token; on failure returns a 400 with validation errors.
 */
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/register',
            status: 200,
            routeName: 'connectholland_user_registration.api',
            output: false,
            openapi: new OpenApiOperation(
                tags: ['Register'],
                summary: 'Register a new user account.',
                responses: [
                    '200' => new OpenApiResponse(description: 'Registration successful.'),
                    '400' => new OpenApiResponse(description: 'Registration failed — validation errors.'),
                ],
            ),
        ),
    ],
    paginationEnabled: false,
)]
class Register
{
    #[ApiProperty(description: 'The e-mail address used to log in.')]
    public string $email = '';

    #[ApiProperty(description: 'The plain-text password chosen by the user.')]
    public string $plainPassword = '';

    #[ApiProperty(description: 'The user must accept the terms of service.')]
    public bool $terms = false;
}
