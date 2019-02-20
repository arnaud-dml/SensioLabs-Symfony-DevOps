<?php

namespace App\Tests\Repository;

use App\Entity\Weather;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WeatherRepositoryTest extends KernelTestCase
{
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testEmptyFindAll()
    {
        $weather = $this->entityManager->getRepository(Weather::class)->findAll();
        self::assertInternalType('array', $weather);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}