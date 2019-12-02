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
use Doctrine\Common\Persistence\ObjectManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @codeCoverageIgnore WIP
 */
final class OAuthUserProvider implements OAuthAwareUserProviderInterface
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var UserResponseInterface
     */
    private $response;

    public function __construct(RegistryInterface $doctrine, EventDispatcherInterface $eventDispatcher)
    {
        $this->doctrine        = $doctrine;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $this->response = $response;

        $name     = $this->response->getResourceOwner()->getName();
        $username = $this->response->getUsername();
        $email    = $this->response->getEmail();
        if (is_null($email)) {
            throw new AccessDeniedException('No e-mailaddress available in this OAuth provider. Can\'t connect to it.');
        }

        $user = $this->loadDatabaseUser($name, $username, $email);
        $this->updateUserRoles($user, $name);

        /** @var ObjectManager $objectManager */
        $objectManager = $this->doctrine->getManagerForClass(
            $this->doctrine->getRepository(UserInterface::class)->getClassName()
        );
        $objectManager->persist($user);
        $objectManager->flush();

        return $user;
    }

    private function loadDatabaseUser(string $name, string $username, string $email): UserInterface
    {
        /** @var UserRepository $repository */
        $repository = $this->doctrine->getRepository(UserInterface::class);
        $user       = $repository->findOneByOAuthUsername($name, $username);

        if (is_null($user)) {
            $user = $repository->findOneByEmail($email);

            if (is_null($user)) {
                $event = new CreateOAuthUserEvent($repository->getClassName(), $this->response);
                $this->eventDispatcher->dispatch($event);
                $user = $event->getUser();
            }

            $oauth = new UserOAuth();
            $oauth->setResource($name);
            $oauth->setOAuthUsername($username);

            $user->addOAuth($oauth);

            $this->eventDispatcher->dispatch(new OAuthUserCreatedEvent($user, $this->response));
        }

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
