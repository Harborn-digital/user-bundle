<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Mailer;

use ConnectHolland\UserBundle\Entity\UserInterface;

class DeleteEmail extends BaseEmail implements DeleteEmailInterface
{
    public function send(UserInterface $user): \Swift_Message
    {
        return $this->mailer->createMessageAndSend(
            'delete',
            $user->getEmail(),
            [
                'user' => $user,
            ]
        );
    }
}
