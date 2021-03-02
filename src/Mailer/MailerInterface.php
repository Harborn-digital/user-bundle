<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Mailer;

use Symfony\Component\Mime\Email;

interface MailerInterface
{
    public function createMessageAndSend(string $name, $to, array $parameters = []): Email;
}
