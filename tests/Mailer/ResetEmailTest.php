<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Tests\Event;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Mailer\MailerInterface;
use ConnectHolland\UserBundle\Mailer\ResetEmail;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @coversDefaultClass \ConnectHolland\UserBundle\Mailer\ResetEmail
 */
class ResetEmailTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::send
     * @covers \ConnectHolland\UserBundle\Mailer\BaseEmail::setMailer
     */
    public function testSend()
    {
        $user = (new User())->setEmail('example@example.com')->setPasswordRequestToken('token');

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('generate')
            ->with(
                'connectholland_user_reset_confirm',
                [
                    'token' => 'token',
                    'email' => 'example@example.com',
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
            ->willReturn('http://example.com/wachtwoord-vergeten/example@example.com/token')
        ;

        $mailer = $this->createMock(MailerInterface::class);
        $mailer
            ->expects($this->once())
            ->method('createMessageAndSend')
            ->with(
                'reset',
                'example@example.com',
                [
                    'user' => $user,
                    'link' => 'http://example.com/wachtwoord-vergeten/example@example.com/token?_hash=3Hgb9879PwlKZsoaG%2FKEVlm3B3nPhvaUZkH0ay4Tm%2Fk%3D',
                ]
            )
        ;

        $email = new ResetEmail($router, new UriSigner('secret'));
        $email->setMailer($mailer);
        $email->send($user);
    }
}
