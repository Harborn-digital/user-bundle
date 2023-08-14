<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\UserBundleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\Translation\TranslatorInterface;

class FlashSubscriber implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(RequestStack $requestStack, TranslatorInterface $translator)
    {
        $this->requestStack = $requestStack;
        $this->translator   = $translator;
    }

    /**
     * @codeCoverageIgnore No need to test this array 'config' method
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserBundleEvents::REGISTRATION_COMPLETED   => 'addFlashMessage',
            UserBundleEvents::PASSWORD_RESET_COMPLETED => 'addFlashMessage',
            UserBundleEvents::PASSWORD_RESET_FAILED    => 'addFlashMessage',
            UserBundleEvents::USER_NOT_FOUND           => 'addFlashMessage',
        ];
    }

    public function addFlashMessage(Event $event): void
    {
        $message = $this->translate(sprintf('connectholland_user.flash_message.%s.flash.%s', $event->getAction(), $event->getState()));
        $this->requestStack->getSession()->getFlashBag()->add($event->getState(), $message);
    }

    private function translate(string $message, array $parameters = []): string
    {
        return $this->translator->trans($message, $parameters, 'ConnecthollandUserBundle');
    }
}
