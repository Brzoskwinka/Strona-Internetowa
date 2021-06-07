<?php

namespace App\Repository;

use App\Entity\Bought;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bought|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bought|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bought[]    findAll()
 * @method Bought[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoughtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bought::class);
    }

    // /**
    //  * @return Bought[] Returns an array of Bought objects
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

    /*
    public function findOneBySomeField($value): ?Bought
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
