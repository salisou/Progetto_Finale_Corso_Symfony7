<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/create_Article', name: 'article_create', methods: ['GET', 'POST'])]
    public function createArticle(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setCreatedAt(createdAt: new \DateTimeImmutable()); // Imposta la data di creazione

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_view', ['id' => $article->getId()]);
        }

        return $this->render('article/create.html.twig', [
            'createArticle' => $form,
        ]);
    }

    //__________________________________________________________________________________________________________________________________________


    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        // Limita il numero di articoli per pagina
        $limit = 12;

        // Recupera la pagina corrente dalla richiesta (default: 1)
        $currentPage = $request->query->getInt('page', 1);

        // Ottieni il Paginator dal repository usando il metodo "findPaginated"
        $paginator = $articleRepository->findPaginated($limit, $currentPage);

        // Calcola il numero totale di articoli
        $totalItems = count($paginator);

        // Calcola il numero totale di pagine
        $totalPages = ceil($totalItems / $limit);

        // Determina se ci sono pagine precedenti e successive
        $hasPreviousPage = $currentPage > 1;
        $hasNextPage = $currentPage < $totalPages;

        // Rendi la vista
        return $this->render('article/index.html.twig', [
            'articles' => $paginator,      // Usa il paginatore
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'hasPreviousPage' => $hasPreviousPage,
            'hasNextPage' => $hasNextPage,
        ]);
    }


    //____________________________________________________________________________________________________________________


    #[Route('/article/{id}', name: 'article_view', methods: ['GET'])]
    public function view(Article $article): Response
    {
        // Renderizza una vista per mostrare l'articolo
        return $this->render('article/view.html.twig', [
            'article' => $article,
        ]);
    }

    //____________________________________________________________________________________________________________________

    #[Route('/article/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        // Aggiorna la data di modifica dell'articolo prima di creare il form
        $article->setUpdatedAt(new \DateTimeImmutable());

        // Crea il form basato su PostType e associa il post recuperato
        $form = $this->createForm(ArticleType::class, $article, [
            'is_edit' => true, // Indica che si tratta di una modifica
            'submit_label' => 'Aggiorna' // Passa l'etichetta che desideri per il bottone
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Salva l'articolo
            $em->flush();

            // Redireziona alla pagina di dettaglio dell'articolo
            return $this->redirectToRoute('article_view', ['id' => $article->getId()]);
        }
        // Renderizza il form di modifica se non è stato ancora inviato o non è valido
        // Renderizza il form di modifica se non è stato ancora inviato o non è valido
        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(), // Il form viene passato qui
            'article' => $article, // Aggiungi l'articolo come variabile
        ]);
    }

    //____________________________________________________________________________________________________________________


    #[Route('/article/{id}/delete', name: 'article_delete', methods: ['POST'])]
    public function delete(Article $article, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('app_article');
    }
}