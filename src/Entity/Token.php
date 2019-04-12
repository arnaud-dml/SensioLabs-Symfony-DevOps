<?php

namespace App\Entity;

use App\Common\Entity\Property\PropertyCreatedAtTrait;
use App\Common\Entity\Property\PropertyExpiredAtTrait;
use App\Common\Entity\Property\PropertyIdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TokenRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Token
{
    use PropertyIdTrait;
    use PropertyCreatedAtTrait;
    use PropertyExpiredAtTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gardener", inversedBy="tokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gardener;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getGardener(): Gardener
    {
        return $this->gardener;
    }

    public function setGardener(?Gardener $gardener): self
    {
        $this->gardener = $gardener;

        return $this;
    }
}
