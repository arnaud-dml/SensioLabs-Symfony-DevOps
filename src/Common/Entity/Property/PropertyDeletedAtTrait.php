<?php

namespace App\Common\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait PropertyDeletedAtTrait
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $deletedAt;

    /**
     * @return \DateTimeInterface|null
     */
    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTimeInterface $deletedAt
     *
     * @return self
     */
    public function setDeletedAt(\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function isDelete(): ?\DateTimeInterface
    {
        return $this->getDeletedAt();
    }

    public function recover(): void
    {
        $this->deleteAt = null;
    }
}
