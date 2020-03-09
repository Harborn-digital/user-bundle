<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Mailer;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Mailer\DeleteEmail;
use ConnectHolland\UserBundle\Mailer\MailerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Mailer\DeleteEmail
 */
class DeleteEmailTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::send
     * @covers \ConnectHolland\UserBundle\Mailer\BaseEmail::setMailer
     */
    public function testSend()
    {
        $user   = (new User())->setEmail('example@example.com')->setPasswordRequestToken('token');
        $mailer = $this->createMock(MailerInterface::class);

        $mailer
            ->expects($this->once())
            ->method('createMessageAndSend')
            ->with(
                'delete',
                'example@example.com',
                [
                    'user' => $user,
                ]
            )
        ;

        $email = new DeleteEmail();
        $email->setMailer($mailer);
        $email->send($user);
    }
}
