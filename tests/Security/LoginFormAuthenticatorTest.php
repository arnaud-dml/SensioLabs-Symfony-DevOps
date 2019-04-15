<?php

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginFormAuthenticatorTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testAuthSuccess()
    {
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        $crawler = $this->client->request('GET', $route);
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'johndoe',
            '_password' => 'johndoe',
        ]);
        $form['_remember_me']->tick();
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('homepage');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }

    public function testAuthTargetSuccess()
    {
        $route = $this->client->getContainer()->get('router')->generate('homepage');
        $crawler = $this->client->request('GET', $route);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'johndoe',
            '_password' => 'johndoe',
        ]);
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }

    public function testAuthErrorEmptyUsername()
    {
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        $crawler = $this->client->request('GET', $route);
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'unknown',
            '_password' => '',
        ]);
        $form['_remember_me']->tick();
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }

    public function testLoginErrorEmptyPassword()
    {
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        $crawler = $this->client->request('GET', $route);
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'johndoe',
            '_password' => '',
        ]);
        $form['_remember_me']->tick();
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }

    public function testLoginErrorUserNotFound()
    {
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        $crawler = $this->client->request('GET', $route);
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'unknown',
            '_password' => 'unknown',
        ]);
        $form['_remember_me']->tick();
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }

    public function testAuthErrorCSRF()
    {
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        $crawler = $this->client->request('GET', $route);
        $form = $crawler->selectButton('submit')->form([
            '_username' => 'johndoe',
            '_password' => 'johndoe',
        ]);
        $form['_remember_me']->tick();
        $form['_csrf_token'] = '';
        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }
}
