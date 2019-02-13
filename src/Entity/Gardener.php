<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GardenerRepository")
 */
class Gardener
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pot", cascade={"persist", "remove"}, mappedBy="gardener")
     */
    private $pots;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recipe", cascade={"persist", "remove"}, mappedBy="gardener")
     */
    private $recipes;

    public function __construct()
    {
        $this->pots = new ArrayCollection();
        $this->recipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
    public function addPot(Pot $pot): self
    {
        if (!$this->pots->contains($pot)) {
            $this->pots[] = $pot;
            $pot->setGardener($this);
        }

        return $this;
    }

    public function removePot(Pot $pot): self
    {
        if ($this->pots->contains($pot)) {
            $this->pots->removeElement($pot);
            if ($pot->getGardener() === $this) {
                $pot->setGardener(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Application[]
     */
    public function getPots(): Collection
    {
        return $this->pots;
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
            if ($recipe->getGardener() === $this) {
                $recipe->setGardener(null);
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
