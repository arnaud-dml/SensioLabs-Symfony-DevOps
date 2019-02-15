<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\Gardener;

class GardenerChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $gardener)
    {
        if (!$gardener instanceof Gardener) {
            throw new AccessDeniedException();
        }
    }

    public function checkPostAuth(UserInterface $gardener)
    {
        if (!$gardener instanceof Gardener) {
            throw new AccessDeniedException();
        }
    }
}
