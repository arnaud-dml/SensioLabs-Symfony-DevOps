<?php

namespace App\Tests\Weather;

use App\Weather\WeatherCommand;
// use App\Weather\WeatherManager;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

// use PHPUnit\Framework\TestCase;
// use Symfony\Component\Console\Application;
// use Symfony\Component\Console\Tester\CommandTester;

// https://github.com/Fidigi/agHome/blob/master/tests/Command/CallWheatherCommandTest.php
// http://www.inanzzz.com/index.php/post/c7jb/testing-symfony-console-command-with-phpunit

class WeatherCommandTest extends KernelTestCase
{
    // /** @var MockObject|WeatherManager */
    // private $weatherManager;

    // /** @var CommandTester */
    // private $commandTester;

    // protected function setUp()
    // {
    //     $this->weatherManager = $this->createMock(WeatherManager::class);
    //     $application = new Application();
    //     $application->add(new WeatherCommand($this->weatherManager));
    //     $command = $application->find('weather:create');
    //     $this->commandTester = new CommandTester($command);
    // }

    // public function testExecute()
    // {
    //     $this->commandTester->execute([
    //         'command' => 'weather:create'
    //     ]);

    //     $kernel = static::createKernel();
    //     $application = new Application($kernel);
    //     $application->add($this->weatherCommand);
    //     $command = $application->find('weather:create');
    //     $commandTester = new CommandTester($command);
    //     $commandTester->execute([
    //         'command' => $command->getName()
    //     ]);
    //     $output = $commandTester->getDisplay();
    //     $this->assertContains('Weather has been recovered!', $output);
    // }

    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);
        $command = $application->find('weather:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName()
        ]);
        $output = $commandTester->getDisplay();
        $this->assertContains('Weather has been recovered!', $output);
    }
}
