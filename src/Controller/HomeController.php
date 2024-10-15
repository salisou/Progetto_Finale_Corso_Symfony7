<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        // Verifica se l'utente è autenticato
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // L'utente è autenticato, reindirizzalo alla homepage o un'altra pagina
            return $this->redirectToRoute('app_home');
        }

        // Se l'utente non è autenticato, reindirizzalo alla pagina di login
        return $this->redirectToRoute('app_login');
    }

    #[Route('/home', name: 'app_home')]
    public function article(ArticleRepository $articleRepository, Request $request): Response
    {
        // Limita il numero di articoli per pagina
        $limit = 6;

        // Recupera la pagina corrente dalla richiesta (default: 1)
        $currentPage = $request->query->getInt('page', 1);

        // Ottieni il Paginator dal repository usando il metodo "findPaginated"
        $paginator = $articleRepository->findArticolPaginated($limit, $currentPage);

        // Calcola il numero totale di articoli
        $totalItems = count($paginator);

        // Calcola il numero totale di pagine
        $totalPages = ceil($totalItems / $limit);

        // Determina se ci sono pagine precedenti e successive
        $hasPreviousPage = $currentPage > 1;
        $hasNextPage = $currentPage < $totalPages;

        // Rendi la vista
        return $this->render('home/index.html.twig', [
            'articles' => $paginator,
            'currentPage' => $currentPage,
            'hasPreviousPage' => $hasPreviousPage,
            'hasNextPage' => $hasNextPage,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/featurettes', name: 'featurettes')]
    public function category(CategoryRepository $categoryRepository): Response
    {
        // Recupera le prime 2 categorie dal database
        $categories = $categoryRepository->findBy([], null, 2);

        // Passa le categorie al template
        return $this->render('home/featurettes.html.twig', [
            'cat' => $categories,
        ]);
    }
}
