<?php

namespace App\Tests\Gardener;

use App\Entity\Gardener;
use App\Entity\Token;
use App\Gardener\GardenerManager;
use App\Helper\MailerHelper;
use App\Manager\TokenManager;
use App\Security\Handler\RegisterHandler;
use App\Tests\Common\HandlerExpectsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RegisterHandlerTest extends TestCase
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
     * @var MockObject|MailerHelper
     */
    private $mailerHelper;

    /**
     * @var RegisterHandler
     */
    private $handler;

    protected function setUp()
    {
        $this->form = $this->createMock(FormInterface::class);
        $this->request = $this->createMock(Request::class);
        $this->gardenerManager = $this->createMock(GardenerManager::class);
        $this->tokenManager = $this->createMock(TokenManager::class);
        $this->mailerHelper = $this->createMock(MailerHelper::class);
        $this->handler = new RegisterHandler(
            $this->gardenerManager,
            $this->tokenManager,
            $this->mailerHelper
        );
    }

    private function init()
    {
        $this->formExpectsHandleRequest();
        $this->formExpectsIsSubmitted();
        $this->formExpectsIsValid();

        $this->form
            ->expects(self::once())
            ->method('getData')
            ->willReturn(new Gardener());

        $this->gardenerManager
            ->expects(self::once())
            ->method('register')
            ->with(self::isInstanceOf(Gardener::class))
            ->willReturn(new Gardener());

        $this->tokenManager
            ->expects(self::once())
            ->method('createRegisterToken')
            ->with(self::isInstanceOf(Gardener::class))
            ->willReturn(new Token());
    }

    public function testHandle()
    {
        $this->init();

        $this->mailerHelper
            ->expects(self::once())
            ->method('sendRegisterMail')
            ->with(self::isInstanceOf(Token::class));

        self::assertTrue($this->handler->handle($this->form, $this->request));
    }

    public function testHandleException()
    {
        $this->init();

        $this->mailerHelper
            ->expects(self::once())
            ->method('sendRegisterMail')
            ->with(self::isInstanceOf(Token::class))
            ->willThrowException(new \Exception());

        $this->formExpectsAddError();

        self::assertFalse($this->handler->handle($this->form, $this->request));
    }

    public function testHandleInvalid()
    {
        $this->formExpectsHandleRequest();
        $this->formExpectsIsSubmitted();
        $this->formExpectsIsNotValid();

        self::assertFalse($this->handler->handle($this->form, $this->request));
    }
}
