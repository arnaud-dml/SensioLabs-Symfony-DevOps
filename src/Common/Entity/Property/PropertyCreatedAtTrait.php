<?php

namespace App\Common\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait PropertyCreatedAtTrait
{
    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     *
     * @return self
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->setCreatedAt(new \DateTime());
    }
}
