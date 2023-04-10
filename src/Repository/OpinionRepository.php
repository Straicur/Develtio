<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Opinion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Opinion>
 *
 * @method Opinion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Opinion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Opinion[]    findAll()
 * @method Opinion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpinionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Opinion::class);
    }

    /**
     * @param Opinion $entity
     * @param bool $flush
     * @return void
     */
    public function add(Opinion $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Opinion $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Opinion $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Book $book
     * @return bool
     *
     * Przykład prostego zapytania które wchodzi głębiej w połączenia bazy przy pomocy join
     */
    public function bookHasOpinions(Book $book): bool
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.book', 'b')
            ->where('b.id = :book')
            ->setParameter('book', $book->getId()->toBinary());

        $query = $qb->getQuery();

        $res = $query->execute();

        return count($res) > 0;
    }
//    /**
//     * @return Opinion[] Returns an array of Opinion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Opinion
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
