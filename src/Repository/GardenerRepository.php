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

    // /**
    //  * @return Gardener[] Returns an array of Gardener objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gardener
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
