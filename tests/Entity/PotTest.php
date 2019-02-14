<?php

namespace App\Tests\Entity;

use App\Entity\Pot;
use App\Entity\Gardener;
use App\Entity\Plant;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PotTest extends TestCase
{
    /** @var Pot */
    private $pot;

    protected function setUp()
    {
        $this->pot = new Pot();
    }

    public function testConstruct()
    {
        self::assertInstanceOf(ArrayCollection::class, $this->pot->getPlants());
        self::assertCount(0, $this->pot->getPlants());
    }

    public function testHasId()
    {
        self::assertNull($this->pot->getId());
    }

    public function testHasLocation()
    {
        $location = 'Montparnasse';
        $this->pot->setLocation($location);
        self::assertEquals($location, $this->pot->getLocation());
    }

    public function testHasGardener()
    {
        $gardener = $this->createMock(Gardener::class);
        $this->pot->setGardener($gardener);
        self::assertEquals($gardener, $this->pot->getGardener());
    }

    public function testHasPlants()
    {
        $plant = $this->createMock(Plant::class);

        $this->pot->addPlant($plant);
        self::assertCount(1, $this->pot->getPlants());

        $this->pot->addPlant($plant);
        self::assertCount(1, $this->pot->getPlants());

        $plant->method('getPot')->willReturn($this->pot);

        $this->pot->removePlant($plant);
        self::assertCount(0, $this->pot->getPlants());
    }
}