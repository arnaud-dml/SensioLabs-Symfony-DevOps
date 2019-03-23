<?php

namespace App\Helper;

use App\Common\Helper\LoggerTrait;
use App\Entity\Token;
use App\Manager\TokenManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailerHelper
{
    use LoggerTrait;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $templating;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @required
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->router = $router;
    }

    /**
     * @param Token  $token
     * @return void
     */
    public function register(Token $token): void
    {
        $to = $token->getGardener()->getName() ? [
            $token->getGardener()->getEmail() => $token->getGardener()->getName()
        ] : $token->getGardener()->getEmail();
        $from = [
            'contact@oai.com' => 'Open Agriculture Initiative'
        ];
        $subject = 'Activate your account';
        $body = $this->templating->render('security/mail/register.html.twig', [
            'name' => $token->getGardener()->getName(),
            'link' => $this->router->generate('activate', [
                'token' => $token->getToken()
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
        $this->mail($to, $from, $subject, $body);
        $this->logInfo('Send register mail at ' . $token->getGardener()->getEmail());
    }

    /**
     * @param Token  $token
     * @return void
     */
    public function lostPassword(Token $token): void
    {
        $to = $token->getGardener()->getName() ? [
            $token->getGardener()->getEmail() => $token->getGardener()->getName()
        ] : $token->getGardener()->getEmail();
        $from = [
            'contact@oai.com' => 'Open Agriculture Initiative'
        ];
        $subject = 'Reset your password';
        $body = $this->templating->render('security/mail/lost_password.html.twig', [
            'name' => $token->getGardener()->getName(),
            'link' => $this->router->generate('reset_password', [
                'token' => $token->getToken()
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
        $this->mail($to, $from, $subject, $body);
        $this->logInfo('Send lost password mail at ' . $token->getGardener()->getEmail());
    }

    /**
     * @param string|array $to
     * @param string|array $from
     * @param string $subject
     * @param string $body
     * @param string $format
     * @param string $charset
     * @return void
     */
    public function mail(
        $to,
        $from,
        string $subject,
        string $body,
        string $format = 'text/html',
        string $charset = 'UTF-8'
    ): void {
        $message = (new \Swift_Message())
            ->setCharset($charset)
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $format)
        ;
        $this->mailer->send($message);
    }
}
