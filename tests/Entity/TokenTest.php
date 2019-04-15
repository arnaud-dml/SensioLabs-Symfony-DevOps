<?php

namespace App\Tests\Entity;

use App\Entity\Gardener;
use App\Entity\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    /**
     * @var Token
     */
    private $token;

    protected function setUp()
    {
        $this->token = new Token();
    }

    public function testHasId()
    {
        self::assertNull($this->token->getId());
    }

    public function testHasToken()
    {
        $token = 'token';
        $this->token->setToken($token);
        self::assertEquals($token, $this->token->getToken());
    }

    public function testHasType()
    {
        $type = 'type';
        $this->token->setType($type);
        self::assertEquals($type, $this->token->getType());
    }

    public function testHasGardener()
    {
        $gardener = $this->createMock(Gardener::class);
        $this->token->setGardener($gardener);
        self::assertEquals($gardener, $this->token->getGardener());
    }
}
