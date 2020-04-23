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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PasswordResetFailedSubscriber implements EventSubscriberInterface
{
    /**
     * @var NegotiatorInterface
     */
    private $negotiator;

    /**
     * @var Request|null
     */
    private $request;

    public function __construct(NegotiatorInterface $negotiator, RequestStack $requestStack)
    {
        $this->negotiator = $negotiator;
        $this->request    = $requestStack->getCurrentRequest();
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
        if ($this->request !== null && $this->negotiator->getResult($this->request) === 'json') {
            $response = new JsonResponse(['errors' => ['connectholland_user.password_reset_failed' => 'Resetting the password failed.']], JsonResponse::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
    }
}
