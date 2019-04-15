<?php

namespace App\Tests\Common\Entity\Property;

use App\Common\Entity\Property\PropertyLockedTrait;
use PHPUnit\Framework\TestCase;

class PropertyLockedTraitTest extends TestCase
{
    use PropertyLockedTrait;

    public function testHasFailures()
    {
        self::assertEquals(0, $this->getFailures());
        $this->incFailures();
        self::assertEquals(1, $this->getFailures());
    }

    public function testLockState()
    {
        self::assertFalse($this->isLocked());
        $this->lock(new \DateTime('now +10 min'));
        self::assertNull($this->getFailures());
        self::assertTrue($this->isLocked());
        $this->unlock();
        self::assertFalse($this->isLocked());
    }
}
