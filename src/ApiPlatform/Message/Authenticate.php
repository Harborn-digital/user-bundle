<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\ApiPlatform\Message;

use ApiPlatform\Core\Annotation as Api;

/**
 * Authentication resource.
 *
 * @Api\ApiResource(
 *     attributes={"pagination_enabled"=false},
 *     messenger=true,
 *     collectionOperations={
 *         "authenticate" = {
 *              "method"   = "POST",
 *              "consumes" = {
 *                  "application/json"
 *              },
 *              "produces" = {
 *                  "application/json"
 *              },
 *              "route_name"      = "connectholland_user_login.api",
 *              "swagger_context" = {
 *                  "summary"         = "Authenticate with the API.",
 *                  "tags"            = {"Account"},
 *                  "responses"       = {
 *                      "200" = {
 *                          "description" = "Succesful authenticated, a JWT token will be provided.",
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
 *                          "description" = "Authentication failed, an error will be provided.",
 *                          "schema"      = {
 *                              "type" = "object",
 *                              "properties" = {
 *                                  "code"  = {
 *                                      "type"    = "integer",
 *                                      "default" = 400
 *                                  },
 *                                  "message" = {
 *                                      "type" = "string"
 *                                  },
 *                                  "exception" = {
 *                                      "type" = "object"
 *                                  }
 *                              }
 *                          }
 *                      }
 *                  },
 *              },
 *         }
 *     },
 *     itemOperations={}
 * )
 */
class Authenticate
{
    /**
     * @var string
     * @Api\ApiProperty(identifier=true)
     */
    public $username;

    /**
     * @var string
     */
    public $password;
}
