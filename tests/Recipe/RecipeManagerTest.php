<?php

namespace App\Tests\Recipe;

use App\Entity\Recipe;
use App\Recipe\RecipeManager;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class RecipeManagerTest extends TestCase
{
    /**
     * @var MockObject|ObjectManager
     */
    private $entityManager;

    /**
     * @var RecipeManager
     */
    private $manager;

    protected function setUp()
    {
        $this->entityManager = $this->createMock(ObjectManager::class);
        $this->manager = new RecipeManager($this->entityManager);
    }

    public function testCreate()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('persist');

        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        $recipe = $this->manager->createFromArray([
            'optimalTemperature' => 22,
            'optimalHydrometry' => 15,
        ]);

        self::assertInstanceOf(Recipe::class, $recipe);
    }

    public function testCreateWithEmptyData()
    {
        $this->expectException(MissingOptionsException::class);
        $this->manager->createFromArray([]);
    }
}
