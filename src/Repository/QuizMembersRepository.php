<?php

namespace App\Repository;

use App\Entity\Quiz;
use App\Entity\QuizMembers;
use App\Entity\User;
use App\Repository\Traits\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuizMembers|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizMembers|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizMembers[]    findAll()
 * @method QuizMembers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizMembersRepository extends ServiceEntityRepository
{
    use Paginator;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuizMembers::class);
    }

    /**
     * @param Quiz  $quiz
     * @param User  $user
     * @param int   $page
     * @param array $options
     *
     * @return Pagerfanta
     */
    public function selectPassingForUserByQuiz(Quiz $quiz, User $user, $page = 1, $options = []): Pagerfanta
    {
        $qb = $this->createQueryBuilder('qm')
            ->select('qm')
            ->andWhere('qm.quiz = :quiz')->setParameter('quiz', $quiz)
            ->andWhere('qm.member = :member')->setParameter('member', $user);

        $qb->orderBy('qm.id', 'DESC');

        return $this->createPaginator($qb->getQuery(), $page, QuizMembers::NUM_ITEMS_FOR_QUIZ_PAGE);
    }
}
