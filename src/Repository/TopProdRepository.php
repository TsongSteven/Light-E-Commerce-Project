<?php

namespace App\Repository;

use App\Entity\TopProd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TopProd|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopProd|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopProd[]    findAll()
 * @method TopProd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopProdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TopProd::class);
    }

    // /**
    //  * @return TopProd[] Returns an array of TopProd objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TopProd
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
