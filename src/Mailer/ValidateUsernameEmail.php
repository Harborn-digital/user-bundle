<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Mailer;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @codeCoverageIgnore WIP
 */
final class ValidateUsernameEmail extends BaseEmail
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var UriSigner
     */
    private $uriSigner;

    public function __construct(RouterInterface $router, UriSigner $uriSigner)
    {
        $this->router    = $router;
        $this->uriSigner = $uriSigner;
    }

    public function send(UserInterface $user): \Swift_Message
    {
        $link = $this->router->generate('connectholland_user_registration_confirm', ['token' => $user->getPasswordRequestToken(), 'email' => $user->getEmail()], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->mailer->createMessageAndSend(
            'validate-username',
            $user->getEmail(),
            [
                'user' => $user,
                'link' => $this->uriSigner->sign($link),
            ]
        );
    }
}
