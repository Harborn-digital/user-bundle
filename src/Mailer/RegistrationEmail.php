<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Mailer;

use ConnectHolland\UserBundle\Entity\User;

class RegistrationEmail extends BaseEmail
{
    public function send(User $user): \Swift_Message
    {
        return $this->mailer->createMessageAndSend('registration', $user->getEmail(), [
            'user' => $user,
        ]);
    }
}
