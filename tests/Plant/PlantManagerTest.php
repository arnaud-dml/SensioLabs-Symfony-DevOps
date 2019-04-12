<?php

namespace App\Tests\Plant;

use App\Entity\Plant;
use App\Plant\PlantManager;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class PlantManagerTest extends TestCase
{
    /**
     * @var MockObject|ObjectManager
     */
    private $entityManager;

    /**
     * @var PlantManager
     */
    private $manager;

    protected function setUp()
    {
        $this->entityManager = $this->createMock(ObjectManager::class);
        $this->manager = new PlantManager($this->entityManager);
    }

    public function testCreate()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('persist');

        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        $plant = $this->manager->createFromArray([
            'date' => new \DateTime(),
        ]);

        self::assertInstanceOf(Plant::class, $plant);
    }

    public function testCreateWithEmptyData()
    {
        $this->expectException(MissingOptionsException::class);
        $this->manager->createFromArray([]);
    }
}
