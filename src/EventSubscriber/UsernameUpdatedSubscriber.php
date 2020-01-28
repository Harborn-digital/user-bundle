<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Event\UsernameUpdatedEvent;
use ConnectHolland\UserBundle\Mailer\ValidateUsernameEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UsernameUpdatedSubscriber implements EventSubscriberInterface
{
    /**
     * @var ValidateUsernameEmail
     */
    private $email;

    public function __construct(ValidateUsernameEmail $email)
    {
        $this->email = $email;
    }

    public static function getSubscribedEvents()
    {
        return [
            UsernameUpdatedEvent::class => 'createUsernameUpdateRequest',
        ];
    }

    public function createUsernameUpdateRequest(UsernameUpdatedEvent $event): void
    {
        /** @var UserInterface $user */
        $user = $event->getSubject();
        if ($user->isEnabled() === false) {
            $this->email->send($user);
        }
    }
}
