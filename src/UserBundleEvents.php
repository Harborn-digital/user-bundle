<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle;

class UserBundleEvents
{
    const CREATE_USER  = 'connectholland_user.create_user';
    const USER_CREATED = 'connectholland_user.user_created';
}
