<?php

namespace App\Entity;

use App\Common\Entity\EntityUserTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GardenerRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *      fields="email",
 *      message="This email has already been registered"
 * )
 * @UniqueEntity(
 *      fields="username",
 *      message="This username has already been registered"
 * )
 */
class Gardener implements UserInterface, \Serializable
{
    use EntityUserTrait;
    
    /**
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Token", 
     *      orphanRemoval=true, 
     *      mappedBy="user"
     * )
     */
    private $tokens;

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
        $this->tokens = new ArrayCollection();
        $this->pots = new ArrayCollection();
        $this->recipes = new ArrayCollection();
    }

    /**
     * @param Token $pot
     * @return Gardener
     */
    public function addToken(Token $token): self
    {
        if (!$this->tokens->contains($token)) {
            $this->tokens[] = $token;
            $token->setGardener($this);
        }
        return $this;
    }

    /**
     * @param Token $pot
     * @return Gardener
     */
    public function removeToken(Token $token): self
    {
        if ($this->tokens->contains($token)) {
            $this->tokens->removeElement($token);
            if ($token->getGardener() === $this) {
                $token->setGardener(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Application[]
     */
    public function getTokens(): Collection
    {
        return $this->tokens;
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
