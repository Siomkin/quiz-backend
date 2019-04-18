<?php

namespace App\Repository;

use App\Entity\QuizMembers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuizMembers|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizMembers|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizMembers[]    findAll()
 * @method QuizMembers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizMembersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuizMembers::class);
    }

    // /**
    //  * @return QuizMembers[] Returns an array of QuizMembers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuizMembers
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
