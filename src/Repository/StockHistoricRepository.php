<?php

namespace App\Repository;

use App\Entity\StockHistoric;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockHistoric|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockHistoric|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockHistoric[]    findAll()
 * @method StockHistoric[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockHistoricRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockHistoric::class);
    }

    // /**
    //  * @return StockHistoric[] Returns an array of StockHistoric objects
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
    public function findOneBySomeField($value): ?StockHistoric
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
