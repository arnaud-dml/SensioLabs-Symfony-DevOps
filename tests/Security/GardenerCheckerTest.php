<?php

namespace App\Tests\Security;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Security\GardenerChecker;
use App\Entity\Gardener;

class GardenerCheckerTest extends TestCase
{
    public function testCheckPreAuthInstanceOfGardener()
    {
        $checker = new GardenerChecker();
        self::assertNull($checker->checkPreAuth(new Gardener()));

        $this->expectException(AccessDeniedException::class);
        $checker->checkPreAuth(new User('John', 'password'));
    }

    public function testCheckPostAuthInstanceOfGardener()
    {
        $checker = new GardenerChecker();
        self::assertNull($checker->checkPostAuth(new Gardener()));

        $this->expectException(AccessDeniedException::class);
        $checker->checkPostAuth(new User('John', 'password'));
    }
}
