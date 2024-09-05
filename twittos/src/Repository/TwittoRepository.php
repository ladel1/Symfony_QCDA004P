<?php

namespace App\Repository;

use App\Entity\Twitto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Twitto>
 */
class TwittoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Twitto::class);
    }

    public function searchByAuthor(string $keyword):array{
        $em = $this->getEntityManager();
        $dql = "SELECT t FROM App\Entity\Twitto t JOIN t.author a WHERE a.username LIKE :keyword GROUP BY t.author";
        $query  = $em->createQuery($dql);
        $query->setParameter("keyword","%".$keyword."%");
        $query->setMaxResults(5);
        return $query->getResult(); 
    }

    public function searchByContent(string $keyword):array{
        $query= $this->makeSearchQuery($keyword);
        $query->setMaxResults(5);
        return $query->getResult(); 
    }

    public function search(string $keyword){
        $query = $this->makeSearchQuery($keyword);
        return $query->getResult(); 
    }

    private function makeSearchQuery(string $keyword){
        $em = $this->getEntityManager();
        $dql = "SELECT t FROM App\Entity\Twitto t WHERE t.content LIKE :keyword";
        $query  = $em->createQuery($dql);
        $query->setParameter("keyword","%".$keyword."%");
        return $query;
    }


    //    /**
    //     * @return Twitto[] Returns an array of Twitto objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Twitto
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
