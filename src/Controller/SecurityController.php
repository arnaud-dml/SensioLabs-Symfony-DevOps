<?php

namespace App\Controller;

use App\Entity\Gardener;
use App\Security\Form\RegisterType;
use App\Security\Handler\RegisterHandler;
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
     * @Route("/register", name="register")
     */
    public function register(Request $request, RegisterHandler $registerHandler): Response
    {
        $form = $this->createForm(RegisterType::class);
        if ($registerHandler->handle($form, $request)) {
            return $this->redirectToRoute('login');
        }
        return $this->render("security/register.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset", name="reset")
     */
    public function reset(Request $request): Response
    {
        return $this->render("security/reset.html.twig");
    }
}
