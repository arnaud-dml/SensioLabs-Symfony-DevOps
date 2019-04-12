<?php

namespace App\Common\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait PropertyLockedTrait
{
    /**
     * @ORM\Column(name="failures", type="integer", nullable=true)
     *
     * @var int|null
     */
    protected $failures = 0;

    /**
     * @ORM\Column(name="locked_until", type="integer", nullable=true)
     *
     * @var int|null
     */
    protected $lockedUntil;

    /**
     * @return int|null
     */
    public function getFailures(): ?int
    {
        return $this->failures;
    }

    /**
     * @return self
     */
    public function incFailures(): self
    {
        ++$this->failures;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return null !== $this->lockedUntil && $this->lockedUntil > \time();
    }

    /**
     * @param \DateTimeInterface $time
     *
     * @return self
     */
    public function lock(\DateTimeInterface $time): self
    {
        $this->failures = null;
        $this->lockedUntil = $time->getTimestamp();

        return $this;
    }

    /**
     * @return self
     */
    public function unlock(): self
    {
        $this->failures = null;
        $this->lockedUntil = null;

        return $this;
    }
}
