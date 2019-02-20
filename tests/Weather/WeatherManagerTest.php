<?php

namespace App\Tests\Weather;

use App\Entity\Weather;
use App\Weather\WeatherManager;
use App\Weather\WeatherService;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class WeatherManagerTest extends TestCase
{
    /** @var MockObject|ObjectManager */
    private $entityManager;

    /** @var MockObject|WeatherService */
    private $weatherService;

    /** @var WeatherManager */
    private $manager;

    protected function setUp()
    {
        $this->entityManager = $this->createMock(ObjectManager::class);
        $this->weatherService = $this->createMock(WeatherService::class);
        $this->manager = new WeatherManager($this->entityManager, $this->weatherService);
    }

    public function testCreate()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('persist');

        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        $this->weatherService
            ->expects(self::once())
            ->method('getData')
            ->willReturn([
                'location' => '48.8568,2.3508',
                'temperature' => 23.6,
                'humidity' => 14.8,
                'date' => new \DateTime()
            ]);

        $weather = $this->manager->create();
        self::assertInstanceOf(Weather::class, $weather);
    }
}
