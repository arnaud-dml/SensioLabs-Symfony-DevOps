<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GardenerRepository")
 */
class Gardener implements UserInterface, \Serializable
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
     * @var string
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64)
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     * @var array
     */
    private $roles;

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
        $this->roles = [];
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        if (empty($this->roles)) {
            $this->addRole('ROLE_USER');
        }

        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        $this->roles[] = $role;

        return $this;
    }

    public function removeRole(string $role): self
    {
        $roles = $this->getRoles();
        if (($key = array_search($role, $roles)) !== false) {
            unset($roles[$key]);
        }
        $this->setRoles(array_values($roles));

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

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password
        ]);
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized): self
    {
        list (
            $this->id,
            $this->username,
            $this->email,
            $this->password
        ) = unserialize($serialized);

        return $this;
    }
}
