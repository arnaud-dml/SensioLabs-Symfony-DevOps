<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;
use App\Tests\Common\AuthClientTrait;

class SecurityControllerTest extends PantherTestCase
{
    use AuthClientTrait;

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testRouteLogin()
    {
        $crawler = $this->client->request('GET', '/login');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertCount(1, $crawler->filter('form'));
        self::assertCount(1, $crawler->filter('input[name=_username]'));
        self::assertCount(1, $crawler->filter('input[name=_password]'));
        self::assertCount(1, $crawler->filter('input[name=_remember_me]'));
        self::assertCount(1, $crawler->filter('input[name=_csrf_token]'));
        self::assertCount(1, $crawler->filter('button[type=submit]'));
    }

    public function testLoginSuccess()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'johndoe',
            '_password' => 'johndoe'
        ]);
        $form['_remember_me']->tick();
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('homepage');
        self::assertRegExp("/" . preg_quote($route, "/") . "$/", $location);

        // $pantherClient = static::createPantherClient();
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

    public function testLoginAlready()
    {
        $this->authClient($this->client);
        $this->client->request('GET', '/login');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('homepage');
        self::assertRegExp("/" . preg_quote($route, "/") . "$/", $location);
    }

    public function testLoginErrorUsername()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'unknown',
            '_password' => ''
        ]);
        $form['_remember_me']->tick();
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('login');
        self::assertRegExp("/" . preg_quote($route, "/") . "$/", $location);
    }

    public function testLoginErrorPassword()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'johndoe',
            '_password' => ''
        ]);
        $form['_remember_me']->tick();
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('login');
        self::assertRegExp("/" . preg_quote($route, "/") . "$/", $location);
    }

    public function testLoginErrorCSRF()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'johndoe',
            '_password' => 'johndoe'
        ]);
        $form['_remember_me']->tick();
        $form['_csrf_token'] = '';
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('login');
        self::assertRegExp("/" . preg_quote($route, "/") . "$/", $location);
    }

    public function testLoginRedirect()
    {
        $route = $this->client->getContainer()->get('router')->generate('homepage');

        $crawler = $this->client->request('GET', $route);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'johndoe',
            '_password' => 'johndoe'
        ]);
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        self::assertRegExp("/" . preg_quote($route, "/") . "$/", $location);
    }
}
