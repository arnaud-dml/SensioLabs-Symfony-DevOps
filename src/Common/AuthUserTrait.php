<?php

namespace App\Common;

use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\Security\Core\User\UserInterface;

trait AuthUserTrait
{
    /**
     * @param UserInterface $user
     * @param string $providerKey
     */
    private function authUser(UserInterface $user, string $providerKey = 'main'): void
    {
        $token = new PostAuthenticationGuardToken($user, $providerKey, $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_' . $providerKey, serialize($token));
    }
}
