<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Repository;

use ConnectHolland\UserBundle\Entity\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @codeCoverageIgnore WIP
 */
final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, string $class)
    {
        parent::__construct($registry, $class);
    }

    public function findOneByEmail(string $email): ?UserInterface
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByOAuthUsername(string $resource, string $oauthUsername): ?UserInterface
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
