<?php

namespace App\Tests\Entity;

use App\Entity\Gardener;
use App\Entity\Plant;
use App\Entity\Recipe;
use PHPUnit\Framework\TestCase;

class RecipeTest extends TestCase
{
    /** @var Recipe */
    private $recipe;

    protected function setUp()
    {
        $this->recipe = new Recipe();
    }

    public function testHasId()
    {
        self::assertNull($this->recipe->getId());
    }

    public function testHasOptimalTemperature()
    {
        $optimalTemperature = 23;
        $this->recipe->setOptimalTemperature($optimalTemperature);
        self::assertEquals($optimalTemperature, $this->recipe->getOptimalTemperature());
    }

    public function testHasOptimalHydrometry()
    {
        $optimalHydrometry = 14;
        $this->recipe->setOptimalHydrometry($optimalHydrometry);
        self::assertEquals($optimalHydrometry, $this->recipe->getOptimalHydrometry());
    }

    public function testHasGardener()
    {
        $gardener = $this->createMock(Gardener::class);
        $this->recipe->setGardener($gardener);
        self::assertEquals($gardener, $this->recipe->getGardener());
    }

    public function testHasPlant()
    {
        $plant = $this->createMock(Plant::class);
        $this->recipe->setPlant($plant);
        self::assertEquals($plant, $this->recipe->getPlant());
    }
}