<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $auth): Response
    {
        // Ottenere l'errore di login se c'e unp
        $errore = $auth->getLastAuthenticationError();

        // Ottenere l'username inserito dall'utente
        $lastUsername = $auth->getLastUsername();

        return $this->render('login/index.html.twig', [
            'errore' => $errore,
            'last_username'  => $lastUsername,
        ]);
    }


    #[Route('/logout', name: 'app_logout', methods: ['Get'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}