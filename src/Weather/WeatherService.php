<?php

namespace App\Weather;

use Curl\Curl;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WeatherService
{
    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @param Curl $curl
     */
    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }

    /**
     * @param string $latitude
     * @param string $longitude
     * @param string $time
     * @param string $format
     */
    public function getData(string $latitude, string $longitude, string $time = 'now', string $format = 'json')
    {
        $request = [
            'uri' => 'https://api.meteomatics.com',
            'time' => $time,
            'query' => 't_2m:C,relative_humidity_2m:p',
            'location' => $latitude . ',' . $longitude,
            'format' => $format
        ];
        $this->curl->setBasicAuthentication('studdy_laurent', 'KrGbWt7v4N2uT');
        $response = $this->curl->get(implode('/', $request));
        if ($response->error === false && $response->curl_error === false && $response->http_error === false) {
            $data = json_decode($response->response)->data;
            $result = [
                'date' => new \DateTime(),
                'location' => $request['location']
            ];
            foreach ($data as $value) {
                if ($value->parameter == 't_2m:C') {
                    $result['temperature'] = $value->coordinates[0]->dates[0]->value;
                } elseif ($value->parameter == 'relative_humidity_2m:p') {
                    $result['humidity'] = $value->coordinates[0]->dates[0]->value;
                }
            }
        }
        if ($response->error) {
            throw new HttpException($response->error_code, $response->error_message);
        }
        if ($response->curl_error) {
            throw new HttpException($response->curl_error_code, $response->curl_error_message);
        }
        if ($response->http_error) {
            throw new HttpException($response->http_error_code, $response->http_error_message);
        }
        return $result;
    }
}
