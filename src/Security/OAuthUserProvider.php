<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Security;

use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Entity\UserOAuth;
use ConnectHolland\UserBundle\Event\CreateOAuthUserEvent;
use ConnectHolland\UserBundle\Event\OAuthUserCreatedEvent;
use ConnectHolland\UserBundle\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @codeCoverageIgnore WIP
 */
final class OAuthUserProvider implements OAuthAwareUserProviderInterface
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(ManagerRegistry $doctrine, EventDispatcherInterface $eventDispatcher)
    {
        $this->doctrine        = $doctrine;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        if (is_null($response->getEmail())) {
            throw new AccessDeniedException('No email address available in this OAuth provider. Can\'t connect to it.');
        }

        $user = $this->loadDatabaseUser($response);
        $this->updateUserRoles($user, $response->getResourceOwner()->getName());

        /** @var ObjectManager $objectManager */
        $objectManager = $this->doctrine->getManagerForClass(
            $this->doctrine->getRepository(UserInterface::class)->getClassName()
        );
        $objectManager->persist($user);
        $objectManager->flush();

        return $user;
    }

    private function loadDatabaseUser(UserResponseInterface $response): UserInterface
    {
        $name     = $response->getResourceOwner()->getName();
        $username = $response->getUsername();

        /** @var UserRepository $repository */
        $repository = $this->doctrine->getRepository(UserInterface::class);
        $user       = $repository->findOneByOAuthUsername($name, $username);

        if ($user instanceof UserInterface) {
            return $user;
        }

        if ($response->getEmail() !== '') {
            $user = $repository->findOneByEmail($response->getEmail());
        }

        if (is_null($user)) {
            $event = new CreateOAuthUserEvent($repository->getClassName(), $response);
            $this->eventDispatcher->dispatch($event);
            $user = $event->getUser();
        }

        $oauth = new UserOAuth();
        $oauth->setResource($name);
        $oauth->setOAuthUsername($username);

        $user->addOAuth($oauth);

        $this->eventDispatcher->dispatch(new OAuthUserCreatedEvent($user, $response));

        return $user;
    }

    private function updateUserRoles(UserInterface $user, string $name): void
    {
        $roles   = $user->getRoles();
        $roles[] = 'ROLE_OAUTH';
        $roles[] = 'ROLE_OAUTH_'.strtoupper($name);
        $user->setRoles(array_unique($roles));
    }
}
