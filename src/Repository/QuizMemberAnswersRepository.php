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
}
