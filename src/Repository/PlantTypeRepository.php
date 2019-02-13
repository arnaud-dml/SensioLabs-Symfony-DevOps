<?php

namespace App\Repository;

use App\Entity\PlantType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlantType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlantType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlantType[]    findAll()
 * @method PlantType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlantTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlantType::class);
    }

    // /**
    //  * @return PlantType[] Returns an array of PlantType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlantType
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
