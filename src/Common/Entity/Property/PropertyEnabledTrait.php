<?php

namespace App\Common\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait PropertyEnabledTrait
{
    /**
     * @ORM\Column(type="enabled", type="boolean", nullable=false)
     * @var bool
     */
    private $isEnabled = true;

    /**
     * @return bool
     */
    public function getEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $enabled
     * @return self
     */
    public function setEnabled(bool $enabled): self
    {
        $this->isEnabled = $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->getEnabled();
    }
}
