<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

class HomepageControllerTest extends PantherTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        self::assertTrue($client->getResponse()->isSuccessful());
        self::assertSame(200, $client->getResponse()->getStatusCode()); 
        /**
         * Fonctionne uniquement avec "WebTestCase -> static::createClient()", 
         * car "PantherTestCase -> static::createPantherClient()" ne récupère  
         * pas de méthode "getResponse()"
         */
        $pantherClient = static::createPantherClient();
        $crawler = $pantherClient->request('GET', '/');
        self::assertContains('Open Agriculture Initiative', $crawler->filter('h1')->text());
        self::assertCount(1, $crawler->filter('meta[charset="UTF-8"]'));
    }
}
