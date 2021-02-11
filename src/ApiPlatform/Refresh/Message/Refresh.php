<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\ApiPlatform\Refresh\Message;

use ApiPlatform\Core\Annotation as Api;
use Symfony\Component\Serializer\Annotation\SerializedName;

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
 *              "route_name"      = "gesdinet_jwt_refresh_token",
 *              "swagger_context" = {
 *                  "summary"         = "Re-Authenticate with the API using a refresh token.",
 *                  "tags"            = {"Account"},
 *                  "responses"       = {
 *                      "200" = {
 *                          "description" = "Succesful authenticated, a new JWT token will be provided.",
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
class Refresh
{
    /**
     * @var string
     *
     * @SerializedName("refresh_token")
     */
    public $refreshToken;
}
