<?php

namespace App\Weather;

use App\Entity\Weather;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Curl\Curl;

class WeatherManager
{
    /**
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @param ObjectManager $entityManager
     */
    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->curl = new Curl();
    }

    /**
     * @param array $data
     *
     * @return Weather
     */
    public function createFromArray(array $data): Weather
    {
        if (empty($data) || (empty($data['location']) && empty($data['temperature']) && empty($data['humidity']))) {
            throw new MissingOptionsException();
        }
        $weather = new Weather();
        $weather->setLocation($data['location']);
        $weather->setTemperature($data['temperature']);
        $weather->setHumidity($data['humidity']);
        $weather->setDate(new \DateTime());
        $this->entityManager->persist($weather);
        $this->entityManager->flush();

        return $weather;
    }

    /**
     * @return Weather
     */
    public function createFromAPI(): Weather
    {
        $api = 'https://api.meteomatics.com/now/t_2m:C,relative_humidity_2m:p';
        $auth = [
            'user' => 'studdy_laurent',
            'pass' => 'KrGbWt7v4N2uT'
        ];
        $query = [
            'location' => '48.8568,2.3508',
            'response' => [
                'format' => 'json'
            ]
        ];
        $this->curl->setBasicAuthentication($auth['user'], $auth['pass']);
        $this->curl->get($api . '/' . $query['location'] . '/' . $query['response']['format']);
        if ($this->curl->error) {
            echo $this->curl->error_code;
        } else {
            $data = ['location' => $query['location']];
            $response = json_decode($this->curl->response)->data;
            foreach ($response as $parameters) {
                if ($parameters->parameter == 't_2m:C') {
                    $data['temperature'] = ($parameters->coordinates[0]->dates[0]->value);
                } elseif ($parameters->parameter == 'relative_humidity_2m:p') {
                    $data['humidity'] = ($parameters->coordinates[0]->dates[0]->value);
                }
            }
        }
        $weather = self::createFromArray($data);
        
        return $weather;
    }
}
