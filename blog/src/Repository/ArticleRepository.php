<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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


    public function search(string $keyword):array{

        $em = $this->getEntityManager();
        $dql = "
                SELECT a 
                FROM App\Entity\Article a
                WHERE a.title LIKE :keyword
                OR a.content LIKE :keyword
        ";
        $query = $em->createQuery($dql);
        $query->setParameter("keyword","%".$keyword."%");
        return $query->getResult();
    }

    public function searchWithQueryBuilder(string $keyword):array{

        $qb = $this->createQueryBuilder("a");
        $qb->where("a.title LIKE :keyword")
            ->orWhere("a.content LIKE :keyword");
        $query = $qb->getQuery();
        $query->setParameter("keyword","%".$keyword."%");
        return $query->getResult();
    }
    

    //    /**
    //     * @return Article[] Returns an array of Article objects
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

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
