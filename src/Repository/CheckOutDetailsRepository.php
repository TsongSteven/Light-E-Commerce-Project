<?php

namespace App\Repository;

use App\Entity\CheckOutDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CheckOutDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method CheckOutDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method CheckOutDetails[]    findAll()
 * @method CheckOutDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckOutDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CheckOutDetails::class);
    }

    // /**
    //  * @return CheckOutDetails[] Returns an array of CheckOutDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CheckOutDetails
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
