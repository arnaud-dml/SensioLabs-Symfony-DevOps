<?php

namespace App\Tests\Common\Entity\Property;

use App\Common\Entity\Property\PropertyDeletedAtTrait;
use PHPUnit\Framework\TestCase;

class PropertyDeletedAtTraitTest extends TestCase
{
    use PropertyDeletedAtTrait;

    public function testHasDeletedAt()
    {
        $deletedAt = new \DateTime();
        $this->setDeletedAt($deletedAt);
        self::assertEquals($deletedAt, $this->getDeletedAt());
    }

    public function testState()
    {
        $deletedAt = new \DateTime();
        $this->setDeletedAt($deletedAt);
        self::assertEquals($deletedAt, $this->isDelete());
        self::assertInstanceOf(\DateTimeInterface::class, $this->isDelete());
        $this->recover();
        self::assertSame($this->isDelete(), null);
    }
}
