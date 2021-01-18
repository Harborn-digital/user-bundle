<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\EventSubscriber;

use ConnectHolland\UserBundle\Event\UpdateEvent;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdatePersistSubscriber implements EventSubscriberInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UpdateEvent::class => 'persistEntity',
        ];
    }

    public function persistEntity(UpdateEvent $event): void
    {
        $data = $event->getSubject();
        $this->saveData($data);
    }

    private function saveData($data): void
    {
        $entityClass = ClassUtils::getClass($data); // get correct class, even for proxy classes

        /** @var \Doctrine\Persistence\ObjectManager $entityManager */
        $entityManager = $this->registry->getManagerForClass($entityClass);
        $entityManager->persist($data);
        $entityManager->flush();
    }
}
