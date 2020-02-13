<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Event\DeleteAccountEvent;
use ConnectHolland\UserBundle\Mailer\DeleteEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DeleteAccountSubscriber implements EventSubscriberInterface
{
    /**
     * @var DeleteEmail
     */
    private $deleteEmail;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    public function __construct(DeleteEmail $deleteEmail, UrlGeneratorInterface $router)
    {
        $this->deleteEmail = $deleteEmail;
        $this->router      = $router;
    }

    /**
     * @codeCoverageIgnore No need to test this array 'config' method
     */
    public static function getSubscribedEvents(): array
    {
        return [
            DeleteAccountEvent::class => 'onDeleteAccountEvent',
        ];
    }

    public function onDeleteAccountEvent(DeleteAccountEvent $event): void
    {
        $this->sendMail($event->getUser());
        $event->setResponse(new RedirectResponse($this->router->generate('connectholland_user_login')));
    }

    private function sendMail(UserInterface $user): void
    {
        $this->deleteEmail->send($user);
    }
}
