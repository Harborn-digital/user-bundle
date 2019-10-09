<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Security;

use ConnectHolland\UserBundle\Entity\User;
use ConnectHolland\UserBundle\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;

class OAuthUserProvider implements OAuthAwareUserProviderInterface
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
        /** @var UserRepository $repository */
        $repository = $this->doctrine->getRepository(User::class);

        return $repository->findOneByOAuthUsername($response->getResourceOwner()->getName(), $response->getUsername());
    }
}
