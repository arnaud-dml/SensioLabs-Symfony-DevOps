<?php

namespace App\Tests\Repository;

use App\Entity\PlantType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlantTypeRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testEmptyFindAll()
    {
        $plantType = $this->entityManager->getRepository(PlantType::class)->findAll();
        self::assertInternalType('array', $plantType);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
