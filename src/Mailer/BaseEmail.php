<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Mailer;

/**
 * @codeCoverageIgnore Will be tested in the implementations
 */
abstract class BaseEmail implements EmailInterface
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
