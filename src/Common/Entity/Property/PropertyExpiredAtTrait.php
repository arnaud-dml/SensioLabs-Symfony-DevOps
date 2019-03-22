<?php

namespace App\Common\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait PropertyExpiredAtTrait
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var \DateTimeInterface|null
     */
    protected $expiredAt;

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expiredAt;
    }

    /**
     * @param \DateTimeInterface|null
     * @return self
     */
    public function setExpiredAt(\DateTimeInterface $expiredAt = null): self
    {
        $this->expiredAt = $expiredAt === null ? 0 : $expiredAt->getTimestamp();
        return $this;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return ($this->expiredAt === 0 || $this->expiredAt < time());
    }
}
