<?php

namespace App\Controller;

use App\Form\ModificaPasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ModificaPasswordController extends AbstractController
{
    #[Route('/modifica/password', name: 'app_modifica_password')]
    public function index(Request  $request, UserPasswordHasherInterface  $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        // Verrifica se l'utent è autenticato se non manda un messaggio
        if (!$user) {
            $this->addFlash('error', 'Devi essere autenticato per accedere a questa pagina');
            return $this->redirectToRoute('app_login');
        }

        // Crea la forma per modificarre la password,
        // passando l'utente e il servizio di hashing della password
        $form = $this->createForm(ModificaPasswordUserType::class, $user, [
            'userPasswordHasher' => $userPasswordHasher
        ]);

        // Ascolta la richiesta di symfony 
        $form->handleRequest($request);

        // verrifica se la forma è stata compilata crenttamente e se è valida
        if ($form->isSubmitted() && $form->isValid()) {
            // Recupero i dati dal form
            // dd($form->getData());

            // Aggiorna il db
            $entityManager->flush();
            // messagio flash 
            $this->addFlash('success', 'La password è stata modificata con successo');

            // redirect
            return $this->redirectToRoute('app_login');
        }

        return $this->render('modifica_password/index.html.twig', [
            'formModificaPassword' => $form->createView(),
        ]);
    }
}