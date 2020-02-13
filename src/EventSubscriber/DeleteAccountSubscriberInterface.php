<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface DeleteAccountSubscriberInterface extends EventSubscriberInterface
{
    public function onDeleteAccountEvent(): void;
}
