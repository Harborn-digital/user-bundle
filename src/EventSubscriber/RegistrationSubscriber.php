<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\PostRegistrationEvent;
use GisoStallenberg\Bundle\ResponseContentNegotiationBundle\Negotiation\NegotiatorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class RegistrationSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly NegotiatorInterface $negotiator, private readonly RequestStack $requestStack)
    {
    }

    /**
     * @codeCoverageIgnore No need to test this array 'config' method
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PostRegistrationEvent::class => [
                ['onPostRegistrationEvent', -255],
            ],
        ];
    }

    public function onPostRegistrationEvent(PostRegistrationEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request !== null && $this->negotiator->getResult($request) === 'json') {
            $response = new JsonResponse([]);
            $event->setResponse($response);
        }
    }
}
