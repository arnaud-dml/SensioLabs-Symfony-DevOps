<?php

namespace App\Controller;

use App\Security\Form\LostPasswordType;
use App\Security\Form\RegisterType;
use App\Security\Form\ResetPasswordType;
use App\Security\Handler\ActivateHandler;
use App\Security\Handler\LostPasswordHandler;
use App\Security\Handler\RegisterHandler;
use App\Security\Handler\ResetPasswordHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route(
 *      name="security_"
 * )
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/signin", name="login")
     *
     * @param AuthenticationUtils $authenticationUtils
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('homepage');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/signout", name="logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/signup", name="register")
     *
     * @param Request         $request
     * @param RegisterHandler $registerHandler
     */
    public function register(Request $request, RegisterHandler $registerHandler): Response
    {
        $form = $this->createForm(RegisterType::class);
        if ($registerHandler->handle($form, $request)) {
            $this->addFlash('success', 'You registered! Check your email to activate your account');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account-activation/{token}", name="account_activation")
     *
     * @param ActivateHandler $handler
     * @param string          $token
     */
    public function accountActivation(ActivateHandler $handler, string $token): Response
    {
        if ($handler->handle($token)) {
            $this->addFlash('success', 'Your account is activate');

            return $this->redirectToRoute('security_login');
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/lost-password", name="lost_password")
     *
     * @param Request             $request
     * @param LostPasswordHandler $handler
     */
    public function lostPassword(Request $request, LostPasswordHandler $handler): Response
    {
        $form = $this->createForm(LostPasswordType::class);
        if ($handler->handle($form, $request)) {
            $this->addFlash('success', 'An email has been sent to you to reset your password');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/lost_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset-password/{token}", name="reset_password")
     *
     * @param Request              $request
     * @param ResetPasswordHandler $handler
     * @param string               $token
     */
    public function resetPassword(Request $request, ResetPasswordHandler $handler, string $token)
    {
        $form = $this->createForm(ResetPasswordType::class);
        if ($handler->handle($form, $request, $token)) {
            $this->addFlash('success', 'Your password has been reset');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView(),
            'token' => $token,
        ]);
    }
}
