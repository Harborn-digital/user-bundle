<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Repository;

use ConnectHolland\UserBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @codeCoverageIgnore WIP
 */
final class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOneByEmail($email): ?User
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByOAuthUsername($resource, $oauthUsername): ?User
    {
        return $this->createQueryBuilder('user')
            ->leftJoin('user.oauths', 'oauths', Expr\Join::WITH, 'oauths.oauthUsername = :oauthUsername')
            ->andWhere('oauths.resource = :resource')
            ->setParameter('resource', $resource)
            ->setParameter('oauthUsername', $oauthUsername)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
