<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Message;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Password reset resource.
 *
 * @ApiResource(
 *     attributes={"pagination_enabled"=false},
 *     messenger=true,
 *     collectionOperations={
 *         "password-reset" = {
 *              "method"   = "POST",
 *              "consumes" = {
 *                  "application/json"
 *              },
 *              "produces" = {
 *                  "application/json"
 *              },
 *              "route_name"      = "connectholland_user_reset.api",
 *              "swagger_context" = {
 *                  "summary"         = "Reset password with the API.",
 *                  "responses"       = {
 *                      "200" = {
 *                          "description" = "The password reset is requested succesfully",
 *                          "schema"      = {
 *                              "type" = "object",
 *                              "properties" = {
 *                                  "token" = {
 *                                      "type" = "string"
 *                                  }
 *                              }
 *                          }
 *                      },
 *                  },
 *              },
 *         }
 *     },
 *     itemOperations={}
 * )
 */
class Reset
{
    /**
     * @var string
     */
    public $username;
}
