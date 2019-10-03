<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Mailer;

abstract class BaseEmail
{
    /**
     * @var Mailer
     */
    protected $mailer;

    public function setMailer(Mailer $mailer): void
    {
        $this->mailer = $mailer;
    }
}
