<?php

namespace App\Controller;

use App\Common\AuthUserTrait;
use App\Entity\Gardener;
use App\Gardener\GardenerRegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    use AuthUserTrait;

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
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $gardener = new Gardener();
        $form = $this->createForm(GardenerRegisterType::class, $gardener);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($gardener, $gardener->getPlainPassword());
            $gardener->setPassword($password);
            $gardener->addRole('ROLE_USER');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gardener);
            $entityManager->flush();
            $this->authUser($gardener);
            return $this->redirectToRoute('homepage');
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
