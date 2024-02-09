<?php

namespace App\Repository;

use App\Entity\TranscodingProcesses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TranscodingProcesses>
 *
 * @method TranscodingProcesses|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranscodingProcesses|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranscodingProcesses[]    findAll()
 * @method TranscodingProcesses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranscodingProcessesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TranscodingProcesses::class);
    }

//    /**
//     * @return TranscodingProcesses[] Returns an array of TranscodingProcesses objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TranscodingProcesses
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
