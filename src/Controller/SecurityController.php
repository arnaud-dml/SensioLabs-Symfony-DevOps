<?php

namespace App\Controller;

use App\Entity\Gardener;
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

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
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
            'error' => $error
        ]);
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, RegisterHandler $registerHandler): Response
    {
        $form = $this->createForm(RegisterType::class);
        if ($registerHandler->handle($form, $request)) {
            return $this->redirectToRoute('login');
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/activate/{token}", name="activate")
     */
    public function activate(ActivateHandler $handler, string $token): Response
    {
        if ($handler->handle($token)) {
            return $this->redirectToRoute('login');
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/lost-password", name="lost_password")
     */
    public function lostPassword(Request $request, LostPasswordHandler $handler): Response
    {
        $form = $this->createForm(LostPasswordType::class);
        if ($handler->handle($form, $request)) {
            return $this->redirectToRoute('homepage');
        }
        return $this->render('security/lost_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset-password/{token}", name="reset_password")
     */
    public function resetPassword(Request $request, ResetPasswordHandler $handler, string $token)
    {
        $form = $this->createForm(ResetPasswordType::class);
        if ($handler->handle($form, $request, $token)) {
            return $this->redirectToRoute('login');
        }
        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView(),
            'token' => $token
        ]);
    }
}
