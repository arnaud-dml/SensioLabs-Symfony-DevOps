<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;
use App\Entity\Gardener;

class HomepageControllerTest extends PantherTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $this->client->request('GET', '/');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('login');
        self::assertRegExp("/".preg_quote($route,"/")."$/", $location);

        // https://symfony.com/doc/current/testing/http_authentication.html
        // $this->client = static::createClient([], [
        //     'PHP_AUTH_USER' => 'johndoe',
        //     'PHP_AUTH_PW'   => 'johndoe',
        // ]);
        $this->logIn();
        $this->client->request('GET', '/');
        self::assertTrue($this->client->getResponse()->isSuccessful());
        self::assertSame(200, $this->client->getResponse()->getStatusCode()); 
        // /**
        //  * Fonctionne uniquement avec "WebTestCase -> static::createClient()", 
        //  * car "PantherTestCase -> static::createPantherClient()" ne récupère  
        //  * pas de méthode "getResponse()"
        //  */
        // $pantherClient = static::createPantherClient();
        // $crawler = $pantherClient->request('GET', '/');
        // sleep(1); // Temporisation pour visualiser le test
        // self::assertContains('Open Agriculture Initiative', $crawler->filter('h1')->text());
        // self::assertCount(1, $crawler->filter('meta[charset="UTF-8"]'));
    }

    private function logIn()
    {
        $gardener = new Gardener();
        $gardener->setUsername('johndoe');
        $gardener->setPassword($passwordEncoder->encodePassword($gardener, 'johndoe'));
        $gardener->addRole('ROLE_USER');
        $token = new UsernamePasswordToken($gardener, null, 'main', ['ROLE_USER']);
        $session = $this->client->getContainer()->get('session');
        $session->set('_security_main', serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
