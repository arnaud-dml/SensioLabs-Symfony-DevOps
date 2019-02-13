<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlantRepository")
 */
class Plant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PlantType", inversedBy="plants")
     */
    private $plantType;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pot", inversedBy="plants")
     */
    private $pot;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recipe", cascade={"persist", "remove"}, mappedBy="plant")
     */
    private $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPlantType(): ?PlantType
    {
        return $this->plantType;
    }

    public function setPlantType(?PlantType $plantType): self
    {
        $this->plantType = $plantType;

        return $this;
    }

    public function getPot(): ?Pot
    {
        return $this->pot;
    }

    public function setPot(?Pot $pot): self
    {
        $this->pot = $pot;

        return $this;
    }
    
    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->setGardener($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
            if ($recipe->getPlant() === $this) {
                $recipe->setPlant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Application[]
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }
}
