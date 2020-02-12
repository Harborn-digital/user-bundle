<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Message;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * User confirmation resource.
 *
 * @ApiResource(
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
 *                  "responses"       = {
 *                      "200" = {
 *                          "description" = "The user e-mail is confirmed succesfully",
 *                          "schema"      = {
 *                          }
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
     */
    public $email;

    /**
     * @var string
     */
    public $token;
}
