<?php

namespace App\Tests\Entity;

use App\Entity\PlantType;
use App\Entity\Plant;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PlantTypeTest extends TestCase
{
    /** @var PlantType */
    private $plantType;

    protected function setUp()
    {
        $this->plantType = new PlantType();
    }

    public function testConstruct()
    {
        self::assertInstanceOf(ArrayCollection::class, $this->plantType->getPlants());
        self::assertCount(0, $this->plantType->getPlants());
    }

    public function testHasId()
    {
        self::assertNull($this->plantType->getId());
    }

    public function testHasName()
    {
        $name = 'Tomate';
        $this->plantType->setName($name);
        self::assertEquals($name, $this->plantType->getName());
    }

    // à exploser en 3 méthodes : add, remove et get
    public function testHasPlants()
    {
        $plant = $this->createMock(Plant::class);

        $this->plantType->addPlant($plant);
        self::assertCount(1, $this->plantType->getPlants());

        $this->plantType->addPlant($plant);
        self::assertCount(1, $this->plantType->getPlants());

        $this->plantType->removePlant($plant);
        self::assertCount(0, $this->plantType->getPlants());

        // $plant->method('getPlantType')->willReturn($this->plantType);
        // $plant->expects(self::once())->method('setPlantType');
        // $this->plantType->addPlant($plant);
        // $this->plantType->removePlant($plant);
    }
}