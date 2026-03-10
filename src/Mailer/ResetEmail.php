<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Mailer;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Symfony\Component\HttpFoundation\UriSigner;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

final class ResetEmail extends BaseEmail implements ResetEmailInterface
{
    public function __construct(private readonly RouterInterface $router, private readonly UriSigner $uriSigner)
    {
    }

    public function send(UserInterface $user): Email
    {
        $link = $this->router->generate('connectholland_user_reset_confirm', ['token' => $user->getPasswordRequestToken(), 'email' => $user->getEmail()], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->mailer->createMessageAndSend(
            'reset',
            $user->getEmail(),
            [
                'user' => $user,
                'link' => $this->uriSigner->sign($link),
            ]
        );
    }
}
