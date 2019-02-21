<?php

namespace App\Tests\Repository;

use App\Entity\Plant;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlantRepositoryTest extends KernelTestCase
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
        $plant = $this->entityManager->getRepository(Plant::class)->findAll();
        self::assertInternalType('array', $plant);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
