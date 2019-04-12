<?php

namespace App\Security;

use App\Entity\Gardener;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof Gardener) {
            throw new AccessDeniedException();
        }
        if (!$user->isEnabled()) {
            throw new DisabledException();
        }
        if ($user->isLocked()) {
            throw new CustomUserMessageAuthenticationException('Your account has been blocked.');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof Gardener) {
            throw new AccessDeniedException();
        }
    }
}
