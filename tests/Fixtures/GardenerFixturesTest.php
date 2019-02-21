<?php

namespace App\Tests\Fixtures;

use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use App\DataFixtures\GardenerFixtures;

class GardenerFixturesTest extends TestCase
{
    /**
     * @var MockObject|UserPasswordEncoder
     */
    private $passwordEncoder;

    /**
     * @var MockObject|ObjectManager
     */
    private $entityManager;

    /**
     * @var GardenerFixtures
     */
    private $gardenerFixtures;

    protected function setUp()
    {
        $this->entityManager = $this->createMock(ObjectManager::class);
        $this->passwordEncoder = $this->createMock(UserPasswordEncoder::class);
        $this->passwordEncoder->method('encodePassword')->willReturn('$2y$13$eT5lvo2e21YJ0NgCRjQlpugu/odlPUsrIfBUuC9JAPErKhSp3Fn1u'); // testtest
        $this->gardenerFixtures = new GardenerFixtures($this->passwordEncoder);
    }

    public function testLoadFixtures()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('persist');

        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        $this->gardenerFixtures->load($this->entityManager);
    }
}
