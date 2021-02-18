<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\ApiPlatform\Message;

use ApiPlatform\Core\Annotation as Api;

/**
 * Account management resource.
 *
 * @Api\ApiResource(
 *     attributes={"pagination_enabled"=false},
 *     messenger=true,
 *     itemOperations={},
 *     collectionOperations={
 *         "account-account" = {
 *              "method"   = "GET",
 *              "produces" = {
 *                  "application/json"
 *              },
 *              "route_name"      = "connectholland_user_account_account.api",
 *              "swagger_context" = {
 *                  "summary"         = "Return user account info",
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
 *         },
 *         "account-account-update" = {
 *              "method"   = "POST",
 *              "produces" = {
 *                  "application/json"
 *              },
 *              "route_name"      = "connectholland_user_account_account.api",
 *              "swagger_context" = {
 *                  "summary"         = "Update account info",
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
class Account
{
    /**
     * @var string
     * @Api\ApiProperty(identifier=true)
     */
    public $email;

    /**
     * @Api\ApiProperty(
     *    attributes = {
     *        "swagger_context" = {
     *           "type"= "object",
     *           "properties" = {
     *               "first" = {
     *                   "type"     = "string",
     *               },
     *               "second" = {
     *                   "type"     = "string",
     *               }
     *           }
     *        }
     *    }
     * )
     */
    public $plainPassword = ['first' => null, 'second' => null];
}
