<?php

namespace App\Repository;

use App\Entity\Gardener;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Gardener|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gardener|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gardener[]    findAll()
 * @method Gardener[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GardenerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Gardener::class);
    }

    /**
     * @param string $login
     * @return Gardener|null
     */
    public function findOneByUsernameOrEmail(string $login): ?Gardener
    {
        return $this->createQueryBuilder('g')
            ->where('g.username = :username OR g.email = :email')
            ->setParameter('username', $login)
            ->setParameter('email', $login)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
