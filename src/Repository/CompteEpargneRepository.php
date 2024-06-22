<?php

namespace App\Repository;

use App\Entity\CompteEpargne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompteEpargne>
 *
 * @method CompteEpargne|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompteEpargne|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompteEpargne[]    findAll()
 * @method CompteEpargne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteEpargneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompteEpargne::class);
    }

//    /**
//     * @return CompteEpargne[] Returns an array of CompteEpargne objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CompteEpargne
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
