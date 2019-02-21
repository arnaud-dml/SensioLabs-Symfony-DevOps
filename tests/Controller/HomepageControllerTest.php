<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;
use App\Tests\Common\AuthClientTrait;
use App\Entity\Gardener;

class HomepageControllerTest extends PantherTestCase
{
    use AuthClientTrait;

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
        self::assertRegExp("/" . preg_quote($route, "/") . "$/", $location);

        $this->authClient($this->client);
        $this->client->request('GET', '/');
        self::assertTrue($this->client->getResponse()->isSuccessful());
        self::assertSame(200, $this->client->getResponse()->getStatusCode());
    }
}
