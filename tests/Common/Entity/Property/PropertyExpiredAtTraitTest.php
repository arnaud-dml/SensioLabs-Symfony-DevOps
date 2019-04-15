<?php

namespace App\Tests\Common\Entity\Property;

use App\Common\Entity\Property\PropertyExpiredAtTrait;
use PHPUnit\Framework\TestCase;

class PropertyExpiredAtTraitTest extends TestCase
{
    use PropertyExpiredAtTrait;

    public function testHasExpiredAt()
    {
        $expiredAt = new \DateTime();
        $this->setExpiredAt($expiredAt);
        self::assertInternalType('int', $this->getExpiredAt());
        self::assertEquals($expiredAt->getTimestamp(), $this->getExpiredAt());
    }

    public function testState()
    {
        $this->setExpiredAt();
        self::assertEquals(0, $this->getExpiredAt());
        self::assertTrue($this->isExpired());
        $this->setExpiredAt(new \DateTime());
        self::assertFalse($this->isExpired());
    }
}
