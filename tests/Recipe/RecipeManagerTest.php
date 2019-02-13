<?php

namespace App\Tests\Recipe;

use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use App\Recipe\RecipeManager;
use App\Recipe\RecipeEntity;

class RecipeManagerTest extends TestCase
{
    /** @var MockObject|ObjectManager */
    private $entityManager;

    /** @var RecipeManager */
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
            'optimalTemperature' => 21,
            'optimalHydrometry' => 15,
        ]);

        self::assertInstanceOf(User::class, $user);

        $this->manager->createFromArray([
            'optimalTemperature' => 21,
            'optimalHydrometry' => 15,
        ]);
    }
}