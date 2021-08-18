<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Item::class);
        $this->paginator = $paginator;
    }

    // /**
    //  * @return Item[] Returns an array of Item objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function getItemsInDesc(){
        return $this->createQueryBuilder('i')
                    ->orderBy('i.id','DESC')
                    ->getQuery()
                    ->getResult()
                    ;
    }
    public function findOrderProduct($id){
        return $this->createQueryBuilder('q')
                ->join('App\Entity\OrderProduct', 'i', 'q.id = i.id')
                ->andWhere('q.id =:id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult()
                ;
    }
    public function findByChildIds(array $value, int $page){
        
        $query = $this->createQueryBuilder('v')
                      ->andWhere('v.category IN (:val)')
                      ->setParameter('val', $value)
                      ->groupBy('v');

                      $query->getQuery();
                      $pagination = $this->paginator->paginate(
                         $query, /* query NOT result */
                         $page, /*page number*/
                         Item::perPage /*limit per page*/
                     );
                     return $pagination;                      
    }

    /*
    public function findOneBySomeField($value): ?Item
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
