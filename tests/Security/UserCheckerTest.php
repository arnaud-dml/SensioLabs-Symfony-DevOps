<?php

namespace App\Tests\Security;

use App\Entity\Gardener;
use App\Security\UserChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\User;

class UserCheckerTest extends TestCase
{
    public function testCheckPreAuth()
    {
        $checker = new UserChecker();
        self::assertNull($checker->checkPreAuth(new Gardener()));

        $this->expectException(AccessDeniedException::class);
        $checker->checkPreAuth(new User('username', 'password'));
    }

    public function testCheckPreAuthAccountDisabled()
    {
        $checker = new UserChecker();
        $this->expectException(DisabledException::class);
        $checker->checkPreAuth(
            (new Gardener())->setEnabled(false)
        );
    }

    public function testCheckPreAuthAccountBlocked()
    {
        $checker = new UserChecker();
        $this->expectException(CustomUserMessageAuthenticationException::class);
        $checker->checkPreAuth(
            (new Gardener())->lock(new \DateTime('now +10min'))
        );
    }

    public function testCheckPostAuth()
    {
        $checker = new UserChecker();
        self::assertNull($checker->checkPostAuth(new Gardener()));

        $this->expectException(AccessDeniedException::class);
        $checker->checkPostAuth(new User('username', 'password'));
    }
}
