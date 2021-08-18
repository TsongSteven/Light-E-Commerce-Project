<?php

namespace App\Repository;

use App\Entity\FrontCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FrontCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method FrontCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method FrontCategory[]    findAll()
 * @method FrontCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FrontCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FrontCategory::class);
    }

    // /**
    //  * @return FrontCategory[] Returns an array of FrontCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FrontCategory
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
