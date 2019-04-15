<?php

namespace App\Tests\Repository;

use App\Entity\Token;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TokenRepositoryTest extends KernelTestCase
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
        $token = $this->entityManager->getRepository(Token::class)->findAll();
        self::assertInternalType('array', $token);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
