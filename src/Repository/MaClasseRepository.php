<?php

namespace App\Repository;

use App\Entity\MaClasse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaClasse>
 *
 * @method MaClasse|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaClasse|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaClasse[]    findAll()
 * @method MaClasse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaClasseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaClasse::class);
    }

//    /**
//     * @return MaClasse[] Returns an array of MaClasse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MaClasse
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
