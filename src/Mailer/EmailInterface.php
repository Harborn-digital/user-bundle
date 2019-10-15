<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Mailer;

use ConnectHolland\UserBundle\Entity\UserInterface;

interface EmailInterface
{
    public function setMailer(Mailer $mailer): void;

    public function send(UserInterface $user): \Swift_Message;
}
