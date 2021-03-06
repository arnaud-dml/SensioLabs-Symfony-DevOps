<?php

namespace App\Tests\Repository;

use App\Entity\Gardener;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GardenerRepositoryTest extends KernelTestCase
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
        $gardener = $this->entityManager->getRepository(Gardener::class)->findAll();
        self::assertInternalType('array', $gardener);
    }

    public function testFindOneByUsernameOrEmail()
    {
        $gardener = $this->entityManager->getRepository(Gardener::class)->findOneByUsernameOrEmail('wrong');
        self::assertNull($gardener);

        $gardener = $this->entityManager->getRepository(Gardener::class)->findOneByUsernameOrEmail('johndoe');
        self::assertInstanceOf(Gardener::class, $gardener);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
