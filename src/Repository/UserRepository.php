<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * Questo metodo è utilizzato per aggiornare (riscattare) automaticamente la password dell'utente nel tempo.
     *
     * @param PasswordAuthenticatedUserInterface $user L'utente di cui si desidera aggiornare la password.
     * @param string $newHashedPassword La nuova password hashata da impostare per l'utente.
     *
     * @throws UnsupportedUserException Se l'oggetto passato non è un'istanza di User.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        // Controlla se l'utente passato è un'istanza della classe User.
        // Se non lo è, viene lanciata un'eccezione UnsupportedUserException.
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        // Imposta la nuova password hashata per l'utente.
        $user->setPassword($newHashedPassword);

        // Ottiene l'EntityManager e persiste le modifiche all'utente.
        $this->getEntityManager()->persist($user);

        // Salva le modifiche nel database.
        $this->getEntityManager()->flush();
    }


    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}