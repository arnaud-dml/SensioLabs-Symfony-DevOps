<?php

namespace App\Tests\Repository;

use App\Entity\Pot;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PotRepositoryTest extends KernelTestCase
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
        $pot = $this->entityManager->getRepository(Pot::class)->findAll();
        self::assertInternalType('array', $pot);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
