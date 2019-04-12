<?php

namespace App\Weather;

use App\Entity\Weather;
use Doctrine\Common\Persistence\ObjectManager;

class WeatherManager
{
    /**
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @var WeatherService
     */
    protected $weatherService;

    /**
     * @param ObjectManager  $entityManager
     * @param WeatherService $weatherService
     */
    public function __construct(ObjectManager $entityManager, WeatherService $weatherService)
    {
        $this->entityManager = $entityManager;
        $this->weatherService = $weatherService;
    }

    /**
     * @return Weather
     */
    public function create(): Weather
    {
        $data = $this->weatherService->getData('48.8568', '2.3508');
        $weather = (new Weather())
            ->setLocation($data['location'])
            ->setTemperature($data['temperature'])
            ->setHumidity($data['humidity'])
            ->setDate($data['date'])
        ;
        $this->entityManager->persist($weather);
        $this->entityManager->flush();

        return $weather;
    }
}
