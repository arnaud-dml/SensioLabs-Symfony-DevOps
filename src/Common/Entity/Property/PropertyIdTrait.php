<?php

namespace App\Common\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait PropertyIdTrait
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", options={"unigned":true})
     * @ORM\GeneratedValue()
     * @var int|null
     */
    private $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
