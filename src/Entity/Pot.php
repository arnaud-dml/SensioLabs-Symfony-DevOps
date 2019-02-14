<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PotRepository")
 */
class Pot
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Gardener", inversedBy="pots")
     */
    private $gardener;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Plant", cascade={"persist", "remove"}, mappedBy="pot")
     */
    private $plants;

    public function __construct()
    {
        $this->plants = new ArrayCollection();
    }

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

    public function getGardener(): ?Gardener
    {
        return $this->gardener;
    }

    public function setGardener(?Gardener $gardener): self
    {
        $this->gardener = $gardener;

        return $this;
    }

    public function addPlant(Plant $plant): self
    {
        if (!$this->plants->contains($plant)) {
            $this->plants[] = $plant;
            $plant->setPot($this);
        }

        return $this;
    }

    public function removePlant(Plant $plant): self
    {
        if ($this->plants->contains($plant)) {
            $this->plants->removeElement($plant);
            if ($plant->getPot() === $this) {
                $plant->setPot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Application[]
     */
    public function getPlants(): Collection
    {
        return $this->plants;
    }
}
