<?php

namespace App\Repository;

use App\Entity\QuizMemberAnswers;
use App\Entity\QuizMembers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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

    /**
     * @param QuizMembers $member
     * @param null        $correctAnswered
     *
     * @throws NonUniqueResultException
     *
     * @return int
     */
    public function getAnsweredCount(QuizMembers $member, $correctAnswered = null): int
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->andWhere('a.member = :member')->setParameter('member', $member);

        if ($correctAnswered) {
            $qb->andWhere('a.correct = :correctAnswered')->setParameter('correctAnswered', $correctAnswered);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}
