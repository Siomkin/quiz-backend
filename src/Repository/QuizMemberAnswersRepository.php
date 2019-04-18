<?php

namespace App\Repository;

use App\Entity\QuizMemberAnswers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuizMemberAnswers|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizMemberAnswers|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizMemberAnswers[]    findAll()
 * @method QuizMemberAnswers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizMemberAnswersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuizMemberAnswers::class);
    }

    // /**
    //  * @return QuizMemberAnswers[] Returns an array of QuizMemberAnswers objects
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
    public function findOneBySomeField($value): ?QuizMemberAnswers
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
