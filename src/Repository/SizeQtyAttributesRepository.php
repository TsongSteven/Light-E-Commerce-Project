<?php

namespace App\Repository;

use App\Entity\SizeQtyAttributes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SizeQtyAttributes|null find($id, $lockMode = null, $lockVersion = null)
 * @method SizeQtyAttributes|null findOneBy(array $criteria, array $orderBy = null)
 * @method SizeQtyAttributes[]    findAll()
 * @method SizeQtyAttributes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SizeQtyAttributesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SizeQtyAttributes::class);
    }

    // /**
    //  * @return SizeQtyAttributes[] Returns an array of SizeQtyAttributes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SizeQtyAttributes
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
