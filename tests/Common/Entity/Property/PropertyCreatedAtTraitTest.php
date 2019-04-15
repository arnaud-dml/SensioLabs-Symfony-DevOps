<?php

namespace App\Tests\Common\Entity\Property;

use App\Common\Entity\Property\PropertyCreatedAtTrait;
use PHPUnit\Framework\TestCase;

class PropertyCreatedAtTraitTest extends TestCase
{
    use PropertyCreatedAtTrait;

    public function testHasCreatedAt()
    {
        $createdAt = new \DateTime();
        $this->setCreatedAt($createdAt);
        self::assertEquals($createdAt, $this->getCreatedAt());
    }

    public function testPrePersist()
    {
        $createdAt = new \DateTime();
        $this->prePersist();
        self::assertNotEquals($createdAt, $this->getCreatedAt());
        self::assertInstanceOf(\DateTimeInterface::class, $this->getCreatedAt());
    }
}
