<?php

namespace App\Tests\Repository;

use App\Entity\PlantType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlantTypeRepositoryTest extends KernelTestCase
{
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testEmptyFindAll()
    {
        $gardener = $this->entityManager
            ->getRepository(PlantType::class)
            ->findAll()
        ;

        $this->assertCount(0, $gardener);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}