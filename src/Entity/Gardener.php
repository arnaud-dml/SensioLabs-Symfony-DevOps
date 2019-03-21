<?php

namespace App\Entity;

use App\Common\Entity\EntityUserTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GardenerRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Gardener implements UserInterface, \Serializable
{
    use EntityUserTrait;
    
    /**
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Pot",
     *      cascade={"persist", "remove"},
     *      mappedBy="gardener"
     * )
     */
    private $pots;

    /**
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Recipe",
     *      cascade={"persist", "remove"},
     *      mappedBy="gardener"
     * )
     */
    private $recipes;
    
    /**
     * @return Gardener
     */
    public function __construct()
    {
        $this->pots = new ArrayCollection();
        $this->recipes = new ArrayCollection();
    }

    /**
     * @param Pot $pot
     * @return Gardener
     */
    public function addPot(Pot $pot): self
    {
        if (!$this->pots->contains($pot)) {
            $this->pots[] = $pot;
            $pot->setGardener($this);
        }
        return $this;
    }

    /**
     * @param Pot $pot
     * @return Gardener
     */
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

    /**
     * @param Recipe $recipe
     * @return Gardener
     */
    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->setGardener($this);
        }
        return $this;
    }

    /**
     * @param Recipe $recipe
     * @return Gardener
     */
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
