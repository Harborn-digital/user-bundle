<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Message;

use ApiPlatform\Core\Annotation as Api;

/**
 * User registration resource.
 *
 * @Api\ApiResource(
 *     attributes={"pagination_enabled"=false},
 *     messenger=true,
 *     collectionOperations={
 *         "password-register" = {
 *              "method"   = "POST",
 *              "consumes" = {
 *                  "application/json"
 *              },
 *              "produces" = {
 *                  "application/json"
 *              },
 *              "route_name"      = "connectholland_user_registration.api",
 *              "swagger_context" = {
 *                  "summary"         = "Register password with the API.",
 *                  "tags"            = {"Register"},
 *                  "responses"       = {
 *                      "200" = {
 *                          "description" = "The user is registered succesfully",
 *                          "schema"      = {
 *                              "type" = "object",
 *                              "properties" = {
 *                                  "token" = {
 *                                      "type" = "string"
 *                                  }
 *                              }
 *                          }
 *                      },
 *                      "400" = {
 *                          "description" = "The user could not be registered"
 *                      },
 *                  },
 *              },
 *         }
 *     },
 *     itemOperations={}
 * )
 */
class Register
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $plainPassword;

    /**
     * @var bool
     */
    public $terms;
}
