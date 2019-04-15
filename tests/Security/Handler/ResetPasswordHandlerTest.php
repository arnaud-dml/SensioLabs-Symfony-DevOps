<?php

namespace App\Tests\Gardener;

use App\Entity\Gardener;
use App\Entity\Token;
use App\Gardener\GardenerManager;
use App\Manager\TokenManager;
use App\Repository\TokenRepository;
use App\Security\Handler\ResetPasswordHandler;
use App\Tests\Common\HandlerExpectsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ResetPasswordHandlerTest extends TestCase
{
    use HandlerExpectsTrait;

    /**
     * @var MockObject|FormInterface
     */
    private $form;

    /**
     * @var MockObject|Request
     */
    private $request;

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
     * @var ResetPasswordHandler
     */
    private $handler;

    protected function setUp()
    {
        $this->form = $this->createMock(FormInterface::class);
        $this->request = $this->createMock(Request::class);
        $this->gardenerManager = $this->createMock(GardenerManager::class);
        $this->tokenManager = $this->createMock(TokenManager::class);
        $this->tokenRepository = $this->createMock(TokenRepository::class);
        $this->handler = new ResetPasswordHandler(
            $this->gardenerManager,
            $this->tokenManager,
            $this->tokenRepository
        );
    }

    private function init()
    {
        $this->tokenExpectsFindOneBy();
        $this->formExpectsHandleRequest();
        $this->formExpectsIsSubmitted();
        $this->formExpectsIsValid();

        $this->form
            ->expects(self::once())
            ->method('getData')
            ->willReturn(['plainPassword' => 'johndoe']);

        $this->gardenerManager
            ->expects(self::once())
            ->method('encodePassword')
            ->with(self::isInstanceOf(Gardener::class))
            ->willReturn(
                (new Gardener())
                    ->setUsername('johndoe')
                    ->setEmail('john-doe@gmail.com')
                    ->setPassword('$2y$13$yFTtt40t7456iSPY7bx9euJmz924Um15WRIo6Fg6T8je5cdJYkad.')
            );

        $this->gardenerManager
            ->expects(self::once())
            ->method('save')
            ->with(self::isInstanceOf(Gardener::class));
    }

    public function testHandle()
    {
        $this->init();

        $this->tokenManager
            ->expects(self::once())
            ->method('delete')
            ->with(self::isInstanceOf(Token::class));

        self::assertTrue($this->handler->handle(
            $this->form,
            $this->request,
            'OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc'
        ));
    }

    public function testHandleException()
    {
        $this->init();

        $this->tokenManager
            ->expects(self::once())
            ->method('delete')
            ->with(self::isInstanceOf(Token::class))
            ->willThrowException(new \Exception());

        $this->formExpectsAddError();

        self::assertFalse($this->handler->handle(
            $this->form,
            $this->request,
            'OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc'
        ));
    }

    public function testHandleTokenNotFound()
    {
        $this->tokenRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->formExpectsAddError();

        self::assertFalse($this->handler->handle(
            $this->form,
            $this->request,
            'OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc'
        ));
    }

    public function testHandleTokenExpired()
    {
        $this->tokenExpectsFindOneBy('now -1 day');
        $this->formExpectsAddError();

        self::assertFalse($this->handler->handle(
            $this->form,
            $this->request,
            'OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc'
        ));
    }

    public function testHandleInvalid()
    {
        $this->tokenExpectsFindOneBy();
        $this->formExpectsHandleRequest();
        $this->formExpectsIsSubmitted();
        $this->formExpectsIsNotValid();

        self::assertFalse($this->handler->handle(
            $this->form,
            $this->request,
            'OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc'
        ));
    }
}
