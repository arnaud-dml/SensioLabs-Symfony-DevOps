<?php

namespace App\Tests\Weather;

use App\Entity\Weather;
use App\Weather\WeatherService;
use Curl\Curl;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WeatherServiceTest extends TestCase
{
    /**
     * @var MockObject|Curl
     */
    private $client;

    /**
     * @var WeatherService
     */
    private $service;

    protected function setUp()
    {
        $this->client = $this->createMock(Curl::class);
        $this->service = new WeatherService($this->client);
    }

    public function testGetDataSuccess()
    {
        $this->client
            ->expects(self::once())
            ->method('get')
            ->willReturn(
                (object) array(
                    'error' => false,
                    'curl_error' => false,
                    'http_error' => false,
                    'response' => '{"data":[{"parameter":"t_2m:C","coordinates":[{"lat":48.8568,' .
                        '"lon":2.3508,"dates":[{"date":"2019-02-20T22:40:50Z","value":12.7}]}]},' .
                        '{"parameter":"relative_humidity_2m:p","coordinates":[{"lat":48.8568,' .
                        '"lon":2.3508,"dates":[{"date":"2019-02-20T22:40:50Z","value":61.1}]}]}]}'
                )
            );
        $data = $this->service->getData('48.8568', '2.3508');
        self::assertArrayHasKey('location', $data);
        self::assertArrayHasKey('temperature', $data);
        self::assertArrayHasKey('humidity', $data);
        self::assertArrayHasKey('date', $data);
    }

    public function testGetDataError()
    {
        $this->client
            ->expects(self::once())
            ->method('get')
            ->willReturn(
                (object) array(
                    'error' => true,
                    'curl_error' => false,
                    'http_error' => false,
                    'error_code' => 401,
                    'error_message' => 'Unauthorized'
                )
            );
        $this->expectException(HttpException::class);
        $this->service->getData('48.8568', '2.3508');
    }

    public function testGetDataCurlError()
    {
        $this->client
            ->expects(self::once())
            ->method('get')
            ->willReturn(
                (object) array(
                    'error' => false,
                    'curl_error' => true,
                    'http_error' => false,
                    'curl_error_code' => 401,
                    'curl_error_message' => 'Unauthorized'
                )
            );
        $this->expectException(HttpException::class);
        $this->service->getData('48.8568', '2.3508');
    }

    public function testGetDataHttpError()
    {
        $this->client
            ->expects(self::once())
            ->method('get')
            ->willReturn(
                (object) array(
                    'error' => false,
                    'curl_error' => false,
                    'http_error' => true,
                    'http_error_code' => 401,
                    'http_error_message' => 'Unauthorized'
                )
            );
        $this->expectException(HttpException::class);
        $this->service->getData('48.8568', '2.3508');
    }
}
