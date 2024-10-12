<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProfileController extends AbstractController
{

    // public function index(Request  $request, EntityManagerInterface $em): Response
    // {
    //     $profile = new Profile();

    //     $profile->setDateBirth(new \DateTime());
    //     $profile->setCreatedAt(new \DateTimeImmutable());
    //     $profile->setUpdatedAt(new \DateTimeImmutable());
    //     $form = $this->createForm(ProfileType::class, $profile);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         //salva l'immagine nella cartella public/images
    //         $profile->setCoverPicture('default.png');

    //         $em->persist($profile);
    //         $em->flush();

    //         $this->addFlash(
    //             'success',
    //             'Il tuo profilo è stato creato con successo😊'
    //         );
    //         return $this->redirectToRoute('app_profile_view');
    //     }

    //     return $this->render('profile/index.html.twig', [
    //         'profileform' => $form->createView(),
    //     ]);
    // }


    #[Route('/createProfile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // Ottieni l'utente autenticato
        $user = $this->getUser();

        if (!$user) {
            // Se l'utente non è autenticato, mostra un messaggio di errore e reindirizza alla pagina di login
            $this->addFlash('error', 'Devi essere autenticato per creare un profilo.');
            return $this->redirectToRoute('app_login');
        }

        // Controlla se l'utente ha già un profilo
        $existingProfile = $em->getRepository(Profile::class)->findOneBy(['user' => $user]);
        if ($existingProfile) {
            $this->addFlash('error', 'Hai già un profilo associato al tuo account.');
            return $this->redirectToRoute('app_profiles'); // Reindirizza alla visualizzazione del profilo esistente
        }

        // Crea un nuovo profilo
        $profile = new Profile();
        $profile->setDateBirth(new \DateTime());
        $profile->setCreatedAt(new \DateTimeImmutable());
        $profile->setUpdatedAt(new \DateTimeImmutable());

        // Imposta l'utente autenticato come proprietario del profilo
        $profile->setUser($user);

        // Crea il form
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Salva l'immagine nella cartella 'uploads/profiles'
            $pictureFile = $form['picture']->getData();
            if ($pictureFile) {
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('profiles_directory'),  // Assicurati che questo parametro sia configurato correttamente
                    $newFilename
                );
                $profile->setPicture($newFilename);
            }

            // Salva il profilo nel database
            $em->persist($profile);
            $em->flush();

            $this->addFlash('success', 'Il tuo profilo è stato creato con successo😊');
            return $this->redirectToRoute('app_profiles');
        }

        return $this->render('profile/index.html.twig', [
            'profileform' => $form->createView(),
        ]);
    }



    #[Route('/profiles', name: 'app_profiles')]
    public function list(ProfileRepository $profileRepository, Request $request): Response
    {
        // Limita il numero di articoli per pagina
        $limit = 12;

        // Recupera la pagina corrente dalla richiesta (default: 1)
        $currentPage = $request->query->getInt('page', 1);

        // Ottieni il Paginator dal repository usando il metodo "findPaginated"
        $paginator = $profileRepository->findProfilePaginated($limit, $currentPage);

        // Calcola il numero totale di articoli
        $totalItems = count($paginator);

        // Calcola il numero totale di pagine
        $totalPages = ceil($totalItems / $limit);

        // Determina se ci sono pagine precedenti e successive
        $hasPreviousPage = $currentPage > 1;
        $hasNextPage = $currentPage < $totalPages;

        // Rendi la vista
        return $this->render('profile/list.html.twig', [
            'profiles' => $paginator,      // Usa il paginatore
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'hasPreviousPage' => $hasPreviousPage,
            'hasNextPage' => $hasNextPage,
        ]);
        // return $this->render('profile/list.html.twig', [
        //     'profiles' => $profiles,
        // ]);
    }
}