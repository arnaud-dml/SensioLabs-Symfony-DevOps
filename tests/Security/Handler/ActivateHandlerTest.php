<?php

namespace App\Tests\Gardener;

use App\Entity\Gardener;
use App\Entity\Token;
use App\Gardener\GardenerManager;
use App\Manager\TokenManager;
use App\Repository\TokenRepository;
use App\Security\Handler\ActivateHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ActivateHandlerTest extends TestCase
{
    /**
     * @var MockObject|GardenerManager
     */
    private $gardenerManager;

    /**
     * @var MockObject|TokenManager
     */
    private $tokenManager;

    /**
     * @var MockObject|TokenRepository
     */
    private $tokenRepository;

    /**
     * @var ActivateHandler
     */
    private $handler;

    protected function setUp()
    {
        $this->gardenerManager = $this->createMock(GardenerManager::class);
        $this->tokenManager = $this->createMock(TokenManager::class);
        $this->tokenRepository = $this->createMock(TokenRepository::class);
        $this->handler = new ActivateHandler(
            $this->gardenerManager,
            $this->tokenManager,
            $this->tokenRepository
        );
    }

    public function testHandle()
    {
        $this->tokenRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->willReturn($this->createMock(Token::class));

        $this->gardenerManager
            ->expects(self::once())
            ->method('save')
            ->with(self::isInstanceOf(Gardener::class));

        $this->tokenManager
            ->expects(self::once())
            ->method('delete')
            ->with(self::isInstanceOf(Token::class));

        self::assertTrue($this->handler->handle('OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc'));
    }

    public function testHandleTokenNotFound()
    {
        $this->tokenRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->willReturn(null);

        self::assertFalse($this->handler->handle('OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc'));
    }

    public function testHandleException()
    {
        $this->tokenRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->willReturn($this->createMock(Token::class));

        $this->gardenerManager
            ->expects(self::once())
            ->method('save')
            ->with(self::isInstanceOf(Gardener::class));

        $this->tokenManager
            ->expects(self::once())
            ->method('delete')
            ->with(self::isInstanceOf(Token::class))
            ->willThrowException(new \Exception());

        self::assertFalse($this->handler->handle('OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc'));
    }
}
