<?php

namespace App\Tests\Entity;

use App\Entity\Plant;
use App\Entity\PlantType;
use App\Entity\Pot;
use App\Entity\Recipe;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PlantTest extends TestCase
{
    /** @var Plant */
    private $plant;

    protected function setUp()
    {
        $this->plant = new Plant();
    }

    public function testConstruct()
    {
        self::assertInstanceOf(ArrayCollection::class, $this->plant->getRecipes());
        self::assertCount(0, $this->plant->getRecipes());
    }

    public function testHasId()
    {
        self::assertNull($this->plant->getId());
    }

    public function testHasDate()
    {
        $date = new \DateTime();
        $this->plant->setDate($date);
        self::assertEquals($date, $this->plant->getDate());
    }

    public function testHasPlantType()
    {
        $plantType = $this->createMock(PlantType::class);
        $this->plant->setPlantType($plantType);
        self::assertEquals($plantType, $this->plant->getPlantType());
    }

    public function testHasPot()
    {
        $pot = $this->createMock(Pot::class);
        $this->plant->setPot($pot);
        self::assertEquals($pot, $this->plant->getPot());
    }

    public function testHasRecipes()
    {
        $recipe = $this->createMock(Recipe::class);

        $this->plant->addRecipe($recipe);
        self::assertCount(1, $this->plant->getRecipes());

        $this->plant->addRecipe($recipe);
        self::assertCount(1, $this->plant->getRecipes());

        $recipe->method('getPlant')->willReturn($this->plant);

        $this->plant->removeRecipe($recipe);
        self::assertCount(0, $this->plant->getRecipes());
    }
}