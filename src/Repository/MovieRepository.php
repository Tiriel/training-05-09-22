<?php

namespace App\Repository;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    private int $moviesPerPage;

    public function __construct(ManagerRegistry $registry, int $moviesPerPage)
    {
        parent::__construct($registry, Movie::class);
        $this->moviesPerPage = $moviesPerPage;
    }

    public function add(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function get()
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select('partial m.{id, title}, partial g.{id, name}')
            ->join(Genre::class, 'g')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('m.title', $qb->expr()->literal('%Wars%')),
                    $qb->expr()->lte('m.releasedAt', new \DateTimeImmutable('1980-01-01'))
                )
            )
            ;

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
