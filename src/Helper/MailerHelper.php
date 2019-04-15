<?php

namespace App\Helper;

use App\Common\Helper\LoggerTrait;
use App\Entity\Token;
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
     *
     * @param \Swift_Mailer         $mailer
     * @param \Twig_Environment     $templating
     * @param UrlGeneratorInterface $router
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->router = $router;
    }

    /**
     * @param Token $token
     */
    public function sendRegisterMail(Token $token): void
    {
        $to = $token->getGardener()->getDisplayName() ? [
            $token->getGardener()->getEmail() => $token->getGardener()->getDisplayName(),
        ] : $token->getGardener()->getEmail();
        $from = [
            'contact@oai.com' => 'Open Agriculture Initiative',
        ];
        $subject = 'Activate your account';
        $body = $this->templating->render('security/mail/register.html.twig', [
            'name' => $token->getGardener()->getDisplayName(),
            'link' => $this->router->generate('security_account_activation', [
                'token' => $token->getToken(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        $this->sendMail($to, $from, $subject, $body);
        $this->logInfo('Send register mail at '.$token->getGardener()->getEmail());
    }

    /**
     * @param Token $token
     */
    public function sendLostPasswordMail(Token $token): void
    {
        $to = $token->getGardener()->getDisplayName() ? [
            $token->getGardener()->getEmail() => $token->getGardener()->getDisplayName(),
        ] : $token->getGardener()->getEmail();
        $from = [
            'contact@oai.com' => 'Open Agriculture Initiative',
        ];
        $subject = 'Reset your password';
        $body = $this->templating->render('security/mail/lost_password.html.twig', [
            'name' => $token->getGardener()->getDisplayName(),
            'link' => $this->router->generate('security_reset_password', [
                'token' => $token->getToken(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        $this->sendMail($to, $from, $subject, $body);
        $this->logInfo('Send lost password mail at '.$token->getGardener()->getEmail());
    }

    /**
     * @param string|array $to
     * @param string|array $from
     * @param string       $subject
     * @param string       $body
     * @param string       $format
     * @param string       $charset
     */
    public function sendMail(
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
