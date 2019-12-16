<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\ControllerSuccessEventInterface;
use ConnectHolland\UserBundle\UserBundleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FlashSubscriber implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(SessionInterface $session, TranslatorInterface $translator)
    {
        $this->session    = $session;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserBundleEvents::CONTROLLER_SUCCESS => 'addSuccessFlash',
        ];
    }

    public function addSuccessFlash(ControllerSuccessEventInterface $event): void
    {
        $message = $this->translate(sprintf('connectholland_user.flash_message.%s.flash.%s', $event->getAction(), $event->getFlashType()));
        $this->session->getFlashBag()->add($event->getFlashType(), $message);
    }

    private function translate(string $message, array $parameters = []): string
    {
        return $this->translator->trans($message, $parameters, 'ConnecthollandUserBundle');
    }
}
