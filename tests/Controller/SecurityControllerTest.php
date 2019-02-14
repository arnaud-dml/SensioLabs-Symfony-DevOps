<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

class SecurityControllerTest extends PantherTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        self::assertEquals(200, $client->getResponse()->getStatusCode());

        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/login');
        self::assertCount(1, $client->getCrawler()->filter('form'));
        self::assertCount(1, $client->getCrawler()->filter('input[name=_username]'));
        self::assertCount(1, $client->getCrawler()->filter('input[name=_password]'));
        self::assertCount(1, $client->getCrawler()->filter('input[name=_remember_me]'));
        self::assertCount(1, $client->getCrawler()->filter('input[name=_token]'));
        self::assertCount(1, $client->getCrawler()->filter('button[type=submit]'));

        $form = $crawler->selectButton('ok')->form([
            '_username' => 'johndoe',
            '_password' => 'johndoe'
        ]);
        $form['_remember_me']->tick();
        $client->submit($form);
        sleep(2);
        // self::assertEquals(200, $client->getResponse()->getStatusCode());

        // $crawler = $client->submitForm("ok", [
        //     '_username' => 'johndoe',
        //     '_password' => 'johndoe'
        // ]);
        // self::assertTrue($client->getResponse()->isRedirect());

        // $client->followRedirect();
        // echo $client->getRequest()->getUri();
    }
}
