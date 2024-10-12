<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RestraterController extends AbstractController
{
    #[Route('/resgister', name: 'app_register')]
    public function index(Request  $request, EntityManagerInterface $em): Response
    {
        // Crea l'oggetto User
        $user = new User();

        // Genera la createdAt  e updatedAt
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        // Crea il form di registrazione e associa l'oggetto User al form
        $form = $this->createForm(RegisterType::class, $user);

        // Gestisce la richiesta del form
        $form->handleRequest($request);

        // Verifica se il form è stato inviato e se è valido.
        if ($form->isSubmitted() && $form->isValid()) {
            // Salva l'oggetto User nel database 
            $em->persist($user);
            $em->flush();

            // Mostra un messaggio di conferma
            $this->addFlash('message', 'Registrazione completata con successo!');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'registerform' => $form->createView(),
        ]);
    }
}