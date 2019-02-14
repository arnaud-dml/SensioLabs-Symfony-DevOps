<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

class SecurityControllerTest extends PantherTestCase
{
    public function testLogin()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/login');

        self::assertCount(1, $client->getCrawler()->filter('form'));
        self::assertCount(1, $client->getCrawler()->filter('input[name=_username]'));
        self::assertCount(1, $client->getCrawler()->filter('input[name=_password]'));
        self::assertCount(1, $client->getCrawler()->filter('input[name=_remember_me]'));
        self::assertCount(1, $client->getCrawler()->filter('input[name=_token]'));
        self::assertCount(1, $client->getCrawler()->filter('button[type=submit]'));

        $crawler = $client->submitForm("ok", [
            '_username' => 'johndoe',
            '_password' => 'johndoe'
        ]);
        // self::assertTrue($client->getResponse()->isRedirect());

        // $client->followRedirect();
        // echo $client->getRequest()->getUri();

        sleep(2);

        // -> redirection ok ?
        // -> error ok ?
    }
}
