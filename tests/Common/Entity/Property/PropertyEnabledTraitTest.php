<?php

namespace App\Tests\Common\Entity\Property;

use App\Common\Entity\Property\PropertyEnabledTrait;
use PHPUnit\Framework\TestCase;

class PropertyEnabledTraitTest extends TestCase
{
    use PropertyEnabledTrait;

    public function testHasEnabled()
    {
        self::assertTrue($this->getEnabled());
        $this->setEnabled(false);
        self::assertFalse($this->isEnabled());
    }
}
