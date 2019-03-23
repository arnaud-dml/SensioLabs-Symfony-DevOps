<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

/**
 * @group panther
 */
class SecurityControllerPantherTest extends PantherTestCase
{
    public function testAuth()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/signin');
        self::assertCount(1, $crawler->filter('form'));
        self::assertCount(1, $crawler->filter('input[name=_username]'));
        self::assertCount(1, $crawler->filter('input[name=_password]'));
        self::assertCount(1, $crawler->filter('input[name=_remember_me]'));
        self::assertCount(1, $crawler->filter('input[name=_csrf_token]'));
        self::assertCount(1, $crawler->filter('button[type=submit]'));

        $form = $crawler->selectButton('submit')->form([
            '_username' => 'johndoe',
            '_password' => 'johndoe'
        ]);
        $crawler = $client->submit($form);
        // ?? -> https://symfony.com/doc/current/testing/http_authentication.html
        //self::assertCount('MAIN NAVIGATION', $crawler->filter('.sidebar-menu .header')->html());

        // $client->takeScreenshot('test_screenshot_login.png');
        // sleep(1);
        // $crawler = $client->request('GET', '/');
        // sleep(1); // Temporisation pour visualiser le test
        // self::assertContains('Open Agriculture Initiative', $crawler->filter('h1')->text());
        // self::assertCount(1, $crawler->filter('meta[charset="UTF-8"]'));
    }
}
