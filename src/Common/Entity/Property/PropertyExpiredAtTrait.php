<?php

namespace App\Common\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait PropertyExpiredAtTrait
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    protected $expiredAt;

    /**
     * @return int|null
     */
    public function getExpiredAt(): ?int
    {
        return $this->expiredAt;
    }

    /**
     * @param \DateTimeInterface|null $expiredAt
     *
     * @return self
     */
    public function setExpiredAt(\DateTimeInterface $expiredAt = null): self
    {
        $this->expiredAt = null === $expiredAt ? 0 : $expiredAt->getTimestamp();

        return $this;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return 0 === $this->expiredAt || $this->expiredAt < \time();
    }
}
