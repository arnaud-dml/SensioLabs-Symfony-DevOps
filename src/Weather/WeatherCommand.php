<?php

namespace App\Weather;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WeatherCommand extends Command
{
    /**
     * @var string
     *
     * @todo command "php bin/console $defaultName"
     */
    protected static $defaultName = 'weather:create';

    /**
     * @var WeatherManager
     */
    private $weatherManager;

    /**
     * Constructor.
     *
     * @param WeatherManager $weatherManager
     */
    public function __construct(WeatherManager $weatherManager)
    {
        $this->weatherManager = $weatherManager;

        parent::__construct();
    }

    /**
     * Configure.
     *
     * @todo command "php bin/console list": show a short description
     * @todo command "php bin/console $defaultName --help": show full description
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Load weather data from API')
            ->setHelp('This command load weather data from API and save in database')
        ;
    }

    /**
     * Execute.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln([
            '',
            'Weather Create',
            '==============',
            '',
            'Call API...',
            '',
        ]);
        $weather = $this->weatherManager->create();
        $output->writeln([
            'Weather has been recovered!',
            '-> weather_id = '.$weather->getId(),
            '',
            'Location:    '.$weather->getLocation(),
            'Temperature: '.$weather->getTemperature().' °C',
            'Humidity:    '.$weather->getHumidity().' %',
            '',
        ]);
    }
}
