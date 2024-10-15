<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoriesController extends AbstractController
{

    #[Route('/categories_create', name: 'categories.create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        // Crea una nuova istanza dell'entità Category
        $category = new Category();

        // Crea il form utilizzando CategoryType
        $form = $this->createForm(CategoryType::class, $category);

        // Gestisce i dati del form e li assegna all'entità
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Imposta la data di creazione e aggiornamento
            $category->setCreatedAt(new \DateTimeImmutable());
            $category->setUpdatedAt(new \DateTimeImmutable());

            // Salva la nuova categoria nel database
            $em->persist($category);
            $em->flush();

            // Messaggio di conferma e redirect alla lista delle categorie
            $this->addFlash('success', 'Categoria creata con successo!');

            return $this->redirectToRoute('categories.list');
        }

        // Renderizza il form nel template se non è stato inviato o è incompleto
        return $this->render('categories/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //-----------------------------------------------------------------------------------------
    #[Route('/categories', name: 'app_categories')]
    public function index(CategoryRepository  $category): Response
    {
        //recuperazione degli categorite 
        $categories = $category->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    //-----------------------------------------------------------------------------------------
    #[Route('/categories/show/{id}', name: 'categories.show')]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        // Trova la categoria per l'ID passato
        $category = $em->getRepository(Category::class)->find($id);

        // Se la categoria non esiste, lancia un errore 404
        if (!$category) {
            throw $this->createNotFoundException('La categoria non è stata trovata.');
        }

        // Renderizza il template della categoria
        return $this->render('categories/show.html.twig', [
            'category' => $category,
        ]);
    }

    //-----------------------------------------------------------------------------------------
    #[Route('/categories/edit/{id}', name: 'categories.edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em): Response
    {
        // Trova la categoria per l'ID passato
        $category = $em->getRepository(Category::class)->find($id);

        // Se la categoria non esiste, lancia un errore 404
        if (!$category) {
            throw $this->createNotFoundException('La categoria non è stata trovata.');
        }

        // Crea e gestisci il form di modifica della categoria
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        // Se il form è stato inviato ed è valido, salva i cambiamenti
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUpdatedAt(new \DateTimeImmutable()); // Aggiorna la data di aggiornamento

            $em->flush();

            $this->addFlash('success', 'La categoria è stata aggiornata con successo.');
            return $this->redirectToRoute('categories.show', ['id' => $category->getId()]);
        }

        // Renderizza il form di modifica
        return $this->render('categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    //-----------------------------------------------------------------------------------------

}
