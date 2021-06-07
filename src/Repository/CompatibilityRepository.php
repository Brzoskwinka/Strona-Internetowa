<?php

namespace App\Repository;

use App\Entity\Compatibility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Compatibility|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compatibility|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compatibility[]    findAll()
 * @method Compatibility[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompatibilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compatibility::class);
    }

    public function checkCompatibility(int $prod1, int $prod2)
    {
        $prod1Checkable = $this->createQueryBuilder('c')
            ->select("COUNT (c.id)")
            ->andWhere("c.owner = :prod1")
            ->orWhere("c.slave = :prod1")
            ->setParameter('prod1', $prod1)
            ->getQuery()
            ->getOneOrNullResult();
        if (!$prod1Checkable[1]) {
            return [
                'code' => 1
            ];
        }
        $prod2Checkable = $this->createQueryBuilder('c')
            ->select("COUNT (c.id)")
            ->andWhere("c.owner = :prod2")
            ->orWhere("c.slave = :prod2")
            ->setParameter('prod2', $prod2)
            ->getQuery()
            ->getOneOrNullResult();
        if (!$prod2Checkable[1]) {
            return [
                'code' => 2
            ];
        }
        $qb = $this->createQueryBuilder('c');
        $compatible = $qb
            ->select("COUNT (c.id)")
            ->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->eq("c.owner", ":prod1"),
                    $qb->expr()->eq("c.slave", ":prod2")
                )
            )
            ->orWhere($qb->expr()->andX(
                $qb->expr()->eq("c.owner", ":prod2"),
                $qb->expr()->eq("c.slave", ":prod1")
            ))
            ->setParameter('prod1', $prod1)
            ->setParameter('prod2', $prod2)
            ->getQuery()
            ->getOneOrNullResult();
        if (!!$compatible[1]) {
            return [
                'code' => 3
            ];
        }
        return [
            'code' => 4
        ];
    }

    // /**
    //  * @return Compatibility[] Returns an array of Compatibility objects
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
    public function findOneBySomeField($value): ?Compatibility
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
