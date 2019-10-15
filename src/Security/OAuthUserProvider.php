<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Security;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Entity\UserOAuth;
use ConnectHolland\UserBundle\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;

/**
 * @codeCoverageIgnore WIP
 */
final class OAuthUserProvider implements OAuthAwareUserProviderInterface
{
    /**
     * @var Registry
     */
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $name     = $response->getResourceOwner()->getName();
        $username = $response->getUsername();
        $email    = $response->getEmail();

        $user = $this->loadDatabaseUser($name, $username, $email);
        $this->updateUserRoles($user, $name);

        /** @var ObjectManager $objectManager */
        $objectManager = $this->doctrine->getManagerForClass(User::class);
        $objectManager->persist($user);
        $objectManager->flush();

        return $user;
    }

    private function loadDatabaseUser(string $name, string $username, ?string $email): User
    {
        /** @var UserRepository $repository */
        $repository = $this->doctrine->getRepository(User::class);
        $user       = $repository->findOneByOAuthUsername($name, $username);

        if (is_null($user)) {
            $user = $repository->findOneByEmail($email);

            if (is_null($user)) {
                $user = new User();
                $user->setEnabled(true);
                if ($email) {
                    $user->setEmail($email);
                }
            }

            $oauth = new UserOAuth();
            $oauth->setResource($name);
            $oauth->setOAuthUsername($username);

            $user->addOAuth($oauth);

            // @todo: Dispatch new UserOAuth created here to be able adding more data from $response.
        }

        return $user;
    }

    private function updateUserRoles(User $user, string $name): void
    {
        $roles   = $user->getRoles();
        $roles[] = 'ROLE_OAUTH';
        $roles[] = 'ROLE_OAUTH_'.strtoupper($name);
        $user->setRoles(array_unique($roles));
    }
}
