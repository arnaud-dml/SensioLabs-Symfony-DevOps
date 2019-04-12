<?php

namespace App\Tests\Entity;

use App\Entity\Plant;
use App\Entity\PlantType;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class PlantTypeTest extends TestCase
{
    /**
     * @var PlantType
     */
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

    public function testHasPlants()
    {
        $plant = $this->createMock(Plant::class);

        $this->plantType->addPlant($plant);
        self::assertCount(1, $this->plantType->getPlants());

        $this->plantType->addPlant($plant);
        self::assertCount(1, $this->plantType->getPlants());

        $plant->method('getPlantType')->willReturn($this->plantType);

        $this->plantType->removePlant($plant);
        self::assertCount(0, $this->plantType->getPlants());
    }
}
