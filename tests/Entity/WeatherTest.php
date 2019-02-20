<?php

namespace App\Tests\Entity;

use App\Entity\Weather;
use PHPUnit\Framework\TestCase;

class WeatherTest extends TestCase
{
    /** @var Weather */
    private $weather;

    protected function setUp()
    {
        $this->weather = new Weather();
    }

    public function testHasId()
    {
        self::assertNull($this->weather->getId());
    }

    public function testHasLocation()
    {
        $location = '48.8568,2.3508';
        $this->weather->setLocation($location);
        self::assertEquals($location, $this->weather->getLocation());
    }

    public function testHasTemperature()
    {
        $temperature = 23;
        $this->weather->setTemperature($temperature);
        self::assertEquals($temperature, $this->weather->getTemperature());
    }

    public function testHasHumidity()
    {
        $humidity = 14;
        $this->weather->setHumidity($humidity);
        self::assertEquals($humidity, $this->weather->getHumidity());
    }

    public function testHasDate()
    {
        $date = new \DateTime();
        $this->weather->setDate($date);
        self::assertEquals($date, $this->weather->getDate());
    }
}