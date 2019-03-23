<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Common\AuthClientTrait;

class SecurityControllerTest extends WebTestCase
{
    use AuthClientTrait;

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testRouteLogin()
    {
        $crawler = $this->client->request('GET', '/signin');
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
        $crawler = $this->client->request('GET', '/signin');
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
    }

    public function testLoginAlready()
    {
        $this->authClient($this->client);
        $this->client->request('GET', '/signin');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('homepage');
        self::assertRegExp("/" . preg_quote($route, "/") . "$/", $location);
    }

    public function testLoginErrorUsername()
    {
        $crawler = $this->client->request('GET', '/signin');
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'unknown',
            '_password' => ''
        ]);
        $form['_remember_me']->tick();
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        self::assertRegExp("/" . preg_quote($route, "/") . "$/", $location);
    }

    public function testLoginErrorPassword()
    {
        $crawler = $this->client->request('GET', '/signin');
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'johndoe',
            '_password' => ''
        ]);
        $form['_remember_me']->tick();
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        self::assertRegExp("/" . preg_quote($route, "/") . "$/", $location);
    }

    public function testLoginErrorCSRF()
    {
        $crawler = $this->client->request('GET', '/signin');
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'johndoe',
            '_password' => 'johndoe'
        ]);
        $form['_remember_me']->tick();
        $form['_csrf_token'] = '';
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('security_login');
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
