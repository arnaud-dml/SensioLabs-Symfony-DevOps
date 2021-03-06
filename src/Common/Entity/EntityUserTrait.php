<?php

namespace App\Common\Entity;

use App\Common\Entity\Property\PropertyCreatedAtTrait;
use App\Common\Entity\Property\PropertyEnabledTrait;
use App\Common\Entity\Property\PropertyIdTrait;
use App\Common\Entity\Property\PropertyLockedTrait;
use App\Common\Entity\Property\PropertyNameTrait;
use App\Common\Entity\Property\PropertyUpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait EntityUserTrait
{
    use PropertyIdTrait;
    use PropertyCreatedAtTrait;
    use PropertyUpdatedAtTrait;
    use PropertyEnabledTrait;
    use PropertyLockedTrait;
    use PropertyNameTrait;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotNull
     * @Assert\Length(min=5)
     *
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotNull
     * @Assert\Email
     *
     * @var string
     */
    private $email;

    /**
     * @Assert\Length(min=5, max=20)
     *
     * @var string
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    private $roles = [];

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     *
     * @return self
     */
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        if (empty($this->roles)) {
            $this->addRole('ROLE_USER');
        }

        return $this->roles;
    }

    /**
     * @param array $roles
     *
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string $role
     *
     * @return self
     */
    public function addRole(string $role): self
    {
        if (!\in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param string $role
     *
     * @return self
     */
    public function removeRole(string $role): self
    {
        $roles = $this->getRoles();
        if (false !== ($key = \array_search($role, $roles, true))) {
            unset($roles[$key]);
        }
        $this->setRoles(\array_values($roles));

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * @see \Serializable::serialize()
     *
     * @return string
     */
    public function serialize(): string
    {
        return \serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->isEnabled,
        ]);
    }

    /**
     * @see \Serializable::unserialize()
     *
     * @param mixed $serialized
     *
     * @return self
     */
    public function unserialize($serialized): self
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->isEnabled
        ) = \unserialize($serialized);

        return $this;
    }
}
