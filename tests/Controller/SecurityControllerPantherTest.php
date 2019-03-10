<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

/**
 * @group panther
 */
class SecurityControllerPantherTest extends PantherTestCase
{
    public function testLoginSuccess()
    {
        // $pantherClient = static::createPantherClient('127.0.0.1', 9090);
        // $crawler = $pantherClient->request('GET', '/login');
        // $form = $crawler->selectButton('submit')->form([
        //     '_username' => 'johndoe',
        //     '_password' => 'johndoe'
        // ]);
        // $form['_remember_me']->tick();
        // sleep(1);
        // $pantherClient->submit($form);
        // $pantherClient->takeScreenshot('test_screenshot_login.png');
        // sleep(1);
        // $crawler = $pantherClient->request('GET', '/');
        // sleep(1); // Temporisation pour visualiser le test
        // self::assertContains('Open Agriculture Initiative', $crawler->filter('h1')->text());
        // self::assertCount(1, $crawler->filter('meta[charset="UTF-8"]'));
    }
}
