<?php

namespace App\Tests\Pot;

use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use App\Pot\PotManager;
use App\Entity\Pot;

class PotManagerTest extends TestCase
{
    /** @var MockObject|ObjectManager */
    private $entityManager;

    /** @var PotManager */
    private $manager;

    protected function setUp()
    {
        $this->entityManager = $this->createMock(ObjectManager::class);
        $this->manager = new PotManager($this->entityManager);
    }

    public function testCreate()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('persist');

        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        $pot = $this->manager->createFromArray([
            'location' => 'Montparnasse'
        ]);

        self::assertInstanceOf(Pot::class, $pot);
    }

    public function testCreateWithEmptyData()
    {
        $this->expectException(MissingOptionsException::class);
        $this->manager->createFromArray([]);
    }
}