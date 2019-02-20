<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WeatherRepository")
 */
class Weather
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=0)
     */
    private $temperature;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=0)
     */
    private $humidity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getTemperature()
    {
        return $this->temperature;
    }

    public function setTemperature($temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getHumidity()
    {
        return $this->humidity;
    }

    public function setHumidity($humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
