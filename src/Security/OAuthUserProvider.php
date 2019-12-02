<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Security;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Entity\UserInterface;
use ConnectHolland\UserBundle\Entity\UserOAuth;
use ConnectHolland\UserBundle\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
                $userClass = $repository->getClassName();
                $user      = new $userClass();
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

    private function updateUserRoles(UserInterface $user, string $name): void
    {
        $roles   = $user->getRoles();
        $roles[] = 'ROLE_OAUTH';
        $roles[] = 'ROLE_OAUTH_'.strtoupper($name);
        $user->setRoles(array_unique($roles));
    }
}
