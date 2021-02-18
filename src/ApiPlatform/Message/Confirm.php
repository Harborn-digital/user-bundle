<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\ApiPlatform\Message;

use ApiPlatform\Core\Annotation as Api;

/**
 * User confirmation resource.
 *
 * @Api\ApiResource(
 *     attributes={"pagination_enabled"=false},
 *     messenger=true,
 *     collectionOperations={
 *         "password-confirm" = {
 *              "method"   = "GET",
 *              "consumes" = {
 *                  "application/json"
 *              },
 *              "produces" = {
 *                  "application/json"
 *              },
 *              "route_name"      = "connectholland_user_registration_confirm.api",
 *              "swagger_context" = {
 *                  "summary"         = "Confirm user e-mail with the API.",
 *                  "parameters" = {
 *                      {
 *                          "name" = "email",
 *                          "in" = "path",
 *                          "required" = true,
 *                          "type" = "string"
 *                      },
 *                      {
 *                          "name" = "token",
 *                          "in" = "path",
 *                          "required" = true,
 *                          "type" = "string"
 *                      },
 *                  },
 *                  "tags"            = {"Register"},
 *                  "responses"       = {
 *                      "200" = {
 *                          "description" = "The user e-mail is confirmed succesfully"
 *                      },
 *                  },
 *              },
 *         }
 *     },
 *     itemOperations={}
 * )
 */
class Confirm
{
    /**
     * @var string
     * @Api\ApiProperty(identifier=true)
     */
    public $email;

    /**
     * @var string
     */
    public $token;
}
