<?php

namespace App\Tests\Entity;

use App\Entity\Gardener;
use App\Entity\Pot;
use App\Entity\Recipe;
use App\Entity\Token;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class GardenerTest extends TestCase
{
    /**
     * @var Gardener
     */
    private $gardener;

    protected function setUp()
    {
        $this->gardener = new Gardener();
    }

    public function testConstruct()
    {
        self::assertInstanceOf(ArrayCollection::class, $this->gardener->getTokens());
        self::assertInstanceOf(ArrayCollection::class, $this->gardener->getPots());
        self::assertInstanceOf(ArrayCollection::class, $this->gardener->getRecipes());
        self::assertCount(0, $this->gardener->getTokens());
        self::assertCount(0, $this->gardener->getPots());
        self::assertCount(0, $this->gardener->getRecipes());
    }

    public function testHasTokens()
    {
        $token = $this->createMock(Token::class);

        $this->gardener->addToken($token);
        self::assertCount(1, $this->gardener->getTokens());

        $this->gardener->addToken($token);
        self::assertCount(1, $this->gardener->getTokens());

        $token->method('getGardener')->willReturn($this->gardener);

        $this->gardener->removeToken($token);
        self::assertCount(0, $this->gardener->getTokens());
    }

    public function testHasPots()
    {
        $pot = $this->createMock(Pot::class);

        $this->gardener->addPot($pot);
        self::assertCount(1, $this->gardener->getPots());

        $this->gardener->addPot($pot);
        self::assertCount(1, $this->gardener->getPots());

        $pot->method('getGardener')->willReturn($this->gardener);

        $this->gardener->removePot($pot);
        self::assertCount(0, $this->gardener->getPots());
    }

    public function testHasRecipes()
    {
        $recipe = $this->createMock(Recipe::class);

        $this->gardener->addRecipe($recipe);
        self::assertCount(1, $this->gardener->getRecipes());

        $this->gardener->addRecipe($recipe);
        self::assertCount(1, $this->gardener->getRecipes());

        $recipe->method('getGardener')->willReturn($this->gardener);

        $this->gardener->removeRecipe($recipe);
        self::assertCount(0, $this->gardener->getRecipes());
    }
}
