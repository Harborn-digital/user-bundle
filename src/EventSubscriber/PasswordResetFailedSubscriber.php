<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\PasswordResetFailedEvent;
use ConnectHolland\UserBundle\UserBundleEvents;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Negotiation\NegotiatorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class PasswordResetFailedSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly NegotiatorInterface $negotiator, private readonly RequestStack $requestStack)
    {
    }

    /**
     * @return array<string, array<int, array<int, int|string>>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserBundleEvents::PASSWORD_RESET_FAILED => [
                ['onPostRegistrationEvent', -255],
            ],
        ];
    }

    public function onPostRegistrationEvent(PasswordResetFailedEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request !== null && $this->negotiator->getResult($request) === 'json') {
            $response = new JsonResponse(['errors' => ['connectholland_user.password_reset_failed' => 'Resetting the password failed.']], JsonResponse::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
    }
}
