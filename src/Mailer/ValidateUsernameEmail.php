<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Mailer;

use ConnectHolland\UserBundle\Entity\UserInterface;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Negotiation\NegotiatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
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

    /**
     * @var NegotiatorInterface
     */
    private $negotiator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RouterInterface $router, UriSigner $uriSigner, NegotiatorInterface $negotiator, RequestStack $requestStack)
    {
        $this->router       = $router;
        $this->uriSigner    = $uriSigner;
        $this->negotiator   = $negotiator;
        $this->requestStack = $requestStack;
    }

    public function send(UserInterface $user): \Swift_Message
    {
        $route = $this->getRoute();
        $link  = $this->router->generate($route, ['token' => $user->getPasswordRequestToken(), 'email' => $user->getEmail()], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->mailer->createMessageAndSend(
            'validate-username',
            $user->getEmail(),
            [
                'user' => $user,
                'link' => $this->uriSigner->sign($link),
            ]
        );
    }

    private function getRoute(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request !== null && $this->negotiator->getResult($request) === 'json') {
            return 'connectholland_user_registration_confirm.api';
        }

        return 'connectholland_user_registration_confirm';
    }
}
