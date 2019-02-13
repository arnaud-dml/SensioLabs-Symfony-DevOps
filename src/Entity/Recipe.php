<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $optimalTemperature;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $optimalHydrometry;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gardener", inversedBy="recipes")
     */
    private $gardener;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plant", inversedBy="recipes")
     */
    private $plant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptimalTemperature()
    {
        return $this->optimalTemperature;
    }

    public function setOptimalTemperature($optimalTemperature): self
    {
        $this->optimalTemperature = $optimalTemperature;

        return $this;
    }

    public function getOptimalHydrometry()
    {
        return $this->optimalHydrometry;
    }

    public function setOptimalHydrometry($optimalHydrometry): self
    {
        $this->optimalHydrometry = $optimalHydrometry;

        return $this;
    }

    public function getGardener(): ?Gardener
    {
        return $this->gardener;
    }

    public function setGardener(?Gardener $gardener): self
    {
        $this->gardener = $gardener;

        return $this;
    }

    public function getPlant(): ?Plant
    {
        return $this->plant;
    }

    public function setPlant(?Plant $plant): self
    {
        $this->plant = $plant;

        return $this;
    }
}
