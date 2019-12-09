<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\ControllerFormSuccessEventInterface;
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
            UserBundleEvents::CONTROLLER_FORM_SUCCESS => 'addSuccessFlash',
        ];
    }

    public function addSuccessFlash(ControllerFormSuccessEventInterface $event): void
    {
        dump($event);
        dump(sprintf('connectholland_user.flash_message.%s_form.flash.%s_success', $event->getEntityName(), $event->getAction()));
        die();
        $message = $this->translate(sprintf('connectholland_user.flash_message.%s_form.flash.%s_success', $event->getEntityName(), $event->getAction()));
        $this->session->getFlashBag()->add('success', $message);
    }

    private function translate(string $message, array $parameters = []): string
    {
        return $this->translator->trans($message, $parameters, 'HumanResourcesPortalBundle');
    }
}
