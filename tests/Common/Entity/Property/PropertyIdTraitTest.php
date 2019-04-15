<?php

namespace App\Tests\Common\Entity\Property;

use App\Common\Entity\Property\PropertyIdTrait;
use PHPUnit\Framework\TestCase;

class PropertyIdTraitTest extends TestCase
{
    use PropertyIdTrait;

    public function testHasId()
    {
        self::assertNull($this->getId());
    }
}
