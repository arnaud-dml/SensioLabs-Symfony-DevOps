<?php

namespace App\Tests\Weather;

use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use App\Weather\WeatherManager;
use App\Entity\Weather;

class WeatherManagerTest extends TestCase
{
    /** @var MockObject|ObjectManager */
    private $entityManager;

    /** @var WeatherManager */
    private $manager;

    protected function setUp()
    {
        $this->entityManager = $this->createMock(ObjectManager::class);
        $this->manager = new WeatherManager($this->entityManager);
    }

    // public function testCreate()
    // {
    //     $this->entityManager
    //         ->expects(self::once())
    //         ->method('persist');

    //     $this->entityManager
    //         ->expects(self::once())
    //         ->method('flush');

    //     $weather = $this->manager->createFromArray([
    //         'location' => '48.8568,2.3508',
    //         'temperature' => 23,
    //         'humidity' => 14,
    //         'date' => new \DateTime()
    //     ]);

    //     self::assertInstanceOf(Weather::class, $weather);
    // }

    public function testCreateWithEmptyData()
    {
        $this->expectException(MissingOptionsException::class);
        $this->manager->createFromArray([]);
    }

    public function testCreateFromAPI()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('persist');

        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        $weather = $this->manager->createFromAPI();
        self::assertInstanceOf(Weather::class, $weather);
    }
}