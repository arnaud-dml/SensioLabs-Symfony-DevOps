<?php

namespace App\Common\Entity\Property;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait PropertyNameTrait
{
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(min=3, max=30)
     * @var string
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(min=3, max=30)
     * @var string
     */
    private $lastname;

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return self
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return self
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    /**
     * @return string|null
     */
    public function __toString(): ?string
    {
        return $this->getName();
    }
}
