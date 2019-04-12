<?php

namespace App\Tests\Security;

use App\Entity\Gardener;
use App\Security\UserChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\User;

class GardenerCheckerTest extends TestCase
{
    public function testCheckPreAuthInstanceOfGardener()
    {
        $checker = new UserChecker();
        self::assertNull($checker->checkPreAuth(new Gardener()));

        $this->expectException(AccessDeniedException::class);
        $checker->checkPreAuth(new User('John', 'password'));
    }

    public function testCheckPostAuthInstanceOfGardener()
    {
        $checker = new UserChecker();
        self::assertNull($checker->checkPostAuth(new Gardener()));

        $this->expectException(AccessDeniedException::class);
        $checker->checkPostAuth(new User('John', 'password'));
    }
}
