<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Mailer;

use Symfony\Component\HttpFoundation\Request;
use ConnectHolland\UserBundle\Entity\UserInterface;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Negotiation\NegotiatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UriSigner;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @codeCoverageIgnore WIP
 */
final class RegistrationEmail extends BaseEmail implements RegistrationEmailInterface
{
    public function __construct(private readonly RouterInterface $router, private readonly UriSigner $uriSigner, private readonly NegotiatorInterface $negotiator, private readonly RequestStack $requestStack)
    {
    }

    public function send(UserInterface $user): Email
    {
        $route = $this->getRoute();
        $link  = $this->router->generate($route, ['token' => $user->getPasswordRequestToken(), 'email' => $user->getEmail()], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->mailer->createMessageAndSend(
            'registration',
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
        if ($request instanceof Request && $this->negotiator->getResult($request) === 'json') {
            return 'connectholland_user_registration_confirm.api';
        }

        // By convention the confirmation route = registration route + '_confirm'.
        // This allows custom registration routes (e.g. _volunteer) to have their
        // own confirmation URLs that carry the same route defaults.
        $canonicalRoute = $request?->attributes->get('_canonical_route') ?? 'connectholland_user_registration';

        return $canonicalRoute . '_confirm';
    }
}
