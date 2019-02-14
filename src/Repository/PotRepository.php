<?php

namespace App\Repository;

use App\Entity\Pot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Pot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pot[]    findAll()
 * @method Pot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PotRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pot::class);
    }
}
