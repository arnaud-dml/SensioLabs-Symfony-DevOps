<?php

namespace App\Tests\Helper;

use App\Entity\Gardener;
use App\Entity\Token;
use App\Helper\MailerHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailerHelperTest extends TestCase
{
    /**
     * @var MockObject|Swift_Mailer
     */
    private $mailer;

    /**
     * @var MockObject|Twig_Environment
     */
    private $templating;

    /**
     * @var MockObject|UrlGeneratorInterface
     */
    private $router;

    /**
     * @var MailerHelper
     */
    private $mailerHelper;

    /**
     * @var Token
     */
    private $token;

    protected function setUp()
    {
        $this->mailer = $this->createMock(\Swift_Mailer::class);
        $this->templating = $this->createMock(\Twig_Environment::class);
        $this->router = $this->createMock(UrlGeneratorInterface::class);
        $this->mailerHelper = new MailerHelper(
            $this->mailer,
            $this->templating,
            $this->router
        );

        $this->token = new Token();
        $this->token->setGardener(
            (new Gardener())
                ->setFirstname('Martin')
                ->setLastname('Dupond')
                ->setEmail('dupond.martin@gmail.com')
        );
    }

    public function testSendRegisterMail()
    {
        $this->templating
            ->expects(self::once())
            ->method('render')
            ->willReturn('<html><body><p>Template</p><body></html>');

        $this->router
            ->expects(self::once())
            ->method('generate')
            ->willReturn('http://www.url.com/');

        $this->mailer
            ->expects(self::once())
            ->method('send')
            ->with(self::isInstanceOf(\Swift_Message::class));

        $this->mailerHelper->sendRegisterMail($this->token);
    }

    public function testLostPasswordMail()
    {
        $this->templating
            ->expects(self::once())
            ->method('render')
            ->willReturn('<html><body><p>Template</p><body></html>');

        $this->router
            ->expects(self::once())
            ->method('generate')
            ->willReturn('http://www.url.com/');

        $this->mailer
            ->expects(self::once())
            ->method('send')
            ->with(self::isInstanceOf(\Swift_Message::class));

        $this->mailerHelper->sendLostPasswordMail($this->token);
    }
}
