<?php

namespace App\Tests\Common\Entity\Property;

use App\Common\Entity\Property\PropertyUpdatedAtTrait;
use PHPUnit\Framework\TestCase;

class PropertyUpdatedAtTraitTest extends TestCase
{
    use PropertyUpdatedAtTrait;

    public function testHasUpdatedAt()
    {
        $updatedAt = new \DateTime();
        $this->setUpdatedAt($updatedAt);
        self::assertEquals($updatedAt, $this->getUpdatedAt());
    }

    public function testPreUpdate()
    {
        $updatedAt = new \DateTime();
        $this->preUpdate();
        self::assertNotEquals($updatedAt, $this->getUpdatedAt());
        self::assertInstanceOf(\DateTimeInterface::class, $this->getUpdatedAt());
    }
}
