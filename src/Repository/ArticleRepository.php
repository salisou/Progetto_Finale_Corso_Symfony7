<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }


    /**
     * Metodo per ottenere articoli paginati.
     *
     * Questo metodo esegue una query utilizzando il QueryBuilder per recuperare una lista di articoli
     * ordinati in ordine decrescente per ID e restituisce un oggetto Paginator per gestire la paginazione.
     *
     * @param int $limit Il numero massimo di articoli da visualizzare per pagina (default: 12)
     * @param int $currentPage La pagina corrente che si desidera visualizzare (default: 1)
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator Restituisce un oggetto Paginator per gestire la paginazione
     */
    public function findPaginated(int $limit = 12, int $currentPage = 1)
    {
        // Crea una query utilizzando il QueryBuilder per selezionare gli articoli
        $query = $this->createQueryBuilder('a')
            // Ordina gli articoli in ordine decrescente in base al campo "id"
            ->orderBy('a.id', 'DESC')
            // Ottiene l'oggetto Query corrispondente
            ->getQuery()

            // Imposta l'offset (risultato da cui iniziare a visualizzare) per la paginazione
            // Questo calcolo si basa sulla pagina corrente e il limite di risultati per pagina
            ->setFirstResult(($currentPage - 1) * $limit)

            // Limita il numero di risultati restituiti dalla query al numero specificato nel parametro $limit
            ->setMaxResults($limit);

        // Restituisce un oggetto Paginator che gestisce la paginazione per la query
        // Il secondo parametro true forza il conteggio di tutti gli elementi, il che è utile per la paginazione
        return new Paginator($query, true);
    }


    /**
     * Metodo per ottenere articoli paginati.
     *
     * Questo metodo esegue una query utilizzando il QueryBuilder per recuperare una lista di articoli
     * ordinati in ordine decrescente per ID e restituisce un oggetto Paginator per gestire la paginazione.
     *
     * @param int $limit Il numero massimo di articoli da visualizzare per pagina (default: 3)
     * @param int $currentPage La pagina corrente che si desidera visualizzare (default: 1)
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator Restituisce un oggetto Paginator per gestire la paginazione
     */
    public function findArticolPaginated(int $limit = 6, int $currentPage = 1)
    {

        // Crea una query utilizzando il QueryBuilder per selezionare gli articoli
        $query = $this->createQueryBuilder('a')
            // Ordina gli articoli in ordine decrescente in base al campo "id"
            ->orderBy('a.id', 'DESC')
            // Ottiene l'oggetto Query corrispondente
            ->getQuery()

            // Imposta l'offset (risultato da cui iniziare a visualizzare) per la paginazione
            // Questo calcolo si basa sulla pagina corrente e il limite di risultati per pagina
            ->setFirstResult(($currentPage - 1) * $limit)

            // Limita il numero di risultati restituiti dalla query al numero specificato nel parametro $limit
            ->setMaxResults($limit);

        // Restituisce un oggetto Paginator che gestisce la paginazione per la query
        // Il secondo parametro true forza il conteggio di tutti gli elementi, il che è utile per la paginazione
        return new Paginator($query, true);
    }


    //    /**
    //     * @return Artile[] Returns an array of Artile objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Artile
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
