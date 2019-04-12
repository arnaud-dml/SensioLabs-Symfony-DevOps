<?php

namespace App\Tests\Common;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait AuthClientTrait
{
    /**
     * @param Client $client
     */
    public function authClient(Client $client)
    {
        $session = $client->getContainer()->get('session');
        $firewallName = 'main';
        $firewallContext = 'main';
        $token = new UsernamePasswordToken('user', null, $firewallName, ['ROLE_USER']);
        $session->set('_security_'.$firewallContext, \serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
