<?php

namespace App\Tests\Gardener;

use App\Entity\Gardener;
use App\Entity\Token;
use App\Helper\MailerHelper;
use App\Manager\TokenManager;
use App\Repository\GardenerRepository;
use App\Security\Handler\LostPasswordHandler;
use App\Tests\Common\HandlerExpectsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class LostPasswordHandlerTest extends TestCase
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
     * @var MockObject|GardenerRepository
     */
    private $gardenerRepository;

    /**
     * @var MockObject|TokenManager
     */
    private $tokenManager;

    /**
     * @var MockObject|MailerHelper
     */
    private $mailerHelper;

    /**
     * @var LostPasswordHandler
     */
    private $handler;

    protected function setUp()
    {
        $this->form = $this->createMock(FormInterface::class);
        $this->request = $this->createMock(Request::class);
        $this->gardenerRepository = $this->createMock(GardenerRepository::class);
        $this->tokenManager = $this->createMock(TokenManager::class);
        $this->mailerHelper = $this->createMock(MailerHelper::class);
        $this->handler = new LostPasswordHandler(
            $this->gardenerRepository,
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
            ->willReturn(['login' => 'johndoe']);

        $this->gardenerRepository
            ->expects(self::once())
            ->method('findOneByUsernameOrEmail')
            ->with($this->isType('string'))
            ->willReturn(new Gardener());

        $this->tokenManager
            ->expects(self::once())
            ->method('createLostPasswordToken')
            ->with(self::isInstanceOf(Gardener::class))
            ->willReturn(new Token());
    }

    public function testHandle()
    {
        $this->init();

        $this->mailerHelper
            ->expects(self::once())
            ->method('sendLostPasswordMail')
            ->with(self::isInstanceOf(Token::class));

        self::assertTrue($this->handler->handle($this->form, $this->request));
    }

    public function testHandleException()
    {
        $this->init();

        $this->mailerHelper
            ->expects(self::once())
            ->method('sendLostPasswordMail')
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

    public function testHandleGardenerNotFound()
    {
        $this->formExpectsHandleRequest();
        $this->formExpectsIsSubmitted();
        $this->formExpectsIsValid();

        $this->form
            ->expects(self::once())
            ->method('getData')
            ->willReturn(['login' => 'johndoe']);

        $this->gardenerRepository
            ->expects(self::once())
            ->method('findOneByUsernameOrEmail')
            ->with($this->isType('string'))
            ->willReturn(null);

        $this->formExpectsAddError();

        self::assertFalse($this->handler->handle($this->form, $this->request));
    }
}
