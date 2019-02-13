<?php

namespace App\Tests\PlantType;

use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use App\PlantType\PlantTypeManager;
use App\Entity\PlantType;

class PlantTypeManagerTest extends TestCase
{
    /** @var MockObject|ObjectManager */
    private $entityManager;

    /** @var PlantTypeManager */
    private $manager;

    protected function setUp()
    {
        $this->entityManager = $this->createMock(ObjectManager::class);
        $this->manager = new PlantTypeManager($this->entityManager);
    }

    public function testCreate()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('persist');

        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        $plantType = $this->manager->createFromArray([
            'name' => 'Tomate'
        ]);

        self::assertInstanceOf(PlantType::class, $plantType);
    }

    public function testCreateWithEmptyData()
    {
        $this->expectException(MissingOptionsException::class);
        $this->manager->createFromArray([]);
    }
}