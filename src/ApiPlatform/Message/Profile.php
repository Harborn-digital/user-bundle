<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\ApiPlatform\Message;

use ApiPlatform\Core\Annotation as Api;

/**
 * Profile management resource.
 *
 * @Api\ApiResource(
 *     attributes={"pagination_enabled"=false},
 *     messenger=true,
 *     itemOperations={},
 *     collectionOperations={
 *         "account-profile" = {
 *              "method"   = "GET",
 *              "produces" = {
 *                  "application/json"
 *              },
 *              "route_name"      = "connectholland_user_account_profile.api",
 *              "swagger_context" = {
 *                  "summary"         = "Return profile info",
 *                  "tags"            = {"Account"},
 *                  "responses"       = {
 *                      "200" = {
 *                          "description" = "The user account",
 *                          "schema"      = {
 *                              "$ref" = "#/definitions/User"
 *                          }
 *                      },
 *                  },
 *              }
 *         }
 *     }
 * )
 */
class Profile
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $plainPassword;
}
