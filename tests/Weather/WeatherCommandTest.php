<?php

namespace App\Tests\Weather;

use App\Weather\WeatherCommand;
use App\Weather\WeatherManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class WeatherCommandTest extends TestCase
{
    /** @var MockObject|WeatherManager */
    private $weatherManager;

    /** @var WeatherCommand */
    private $weatherCommand;

    protected function setUp()
    {
        $this->weatherManager = $this->createMock(WeatherManager::class);
        $this->weatherCommand = new WeatherCommand($this->weatherManager);
    }

    public function testExecute()
    {
        $this->weatherManager
            ->expects(self::once())
            ->method('create');
        $application = new Application();
        $application->add($this->weatherCommand);
        $command = $application->find('weather:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => 'weather:create'
        ]);
        $output = $commandTester->getDisplay();
        $this->assertContains('Weather has been recovered!', $output);
    }
}
