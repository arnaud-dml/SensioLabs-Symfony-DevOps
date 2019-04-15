<?php

namespace App\Tests\Common\Entity\Property;

use App\Common\Entity\Property\PropertyNameTrait;
use PHPUnit\Framework\TestCase;

class PropertyNameTraitTest extends TestCase
{
    use PropertyNameTrait;

    public function testHasFirstname()
    {
        $firstname = 'Martin';
        $this->setFirstname($firstname);
        self::assertEquals($firstname, $this->getFirstname());
    }

    public function testHasLastname()
    {
        $lastname = 'Dupond';
        $this->setLastname($lastname);
        self::assertEquals($lastname, $this->getLastname());
    }

    public function testHasDisplayName()
    {
        $firstname = 'Dupond';
        $lastname = 'Dupond';
        $this->setFirstname($firstname);
        $this->setLastname($lastname);
        self::assertEquals($firstname.' '.$lastname, $this->getDisplayName());
        self::assertEquals($this->__toString(), $this->getDisplayName());
    }
}
