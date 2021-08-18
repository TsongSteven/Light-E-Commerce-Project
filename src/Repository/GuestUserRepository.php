<?php

namespace App\Repository;

use App\Entity\GuestUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GuestUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuestUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuestUser[]    findAll()
 * @method GuestUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuestUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuestUser::class);
    }

    // /**
    //  * @return GuestUser[] Returns an array of GuestUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function countOrderProduct(){
        return $this->createQueryBuilder('c')
                    ->select('count(c.order_status)')
                    ->where('c.order_status = process')
                    ->getQuery()
                    ->getSingleScalarResult()
                    ;
    }
    /*
    public function findOneBySomeField($value): ?GuestUser
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
