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

    /**
     * @param Quiz $quiz
     * @param int  $limit
     *
     * @return array
     */
    public function getTopResults(Quiz $quiz, $limit = 3): array
    {
        $qb = $this->createQueryBuilder('qm')
            ->select('qm.id', 'qm.uuid', 'qm.started_at', 'qm.completed_at', 'qm.points',
                'user.name as username',
                'timestampdiff(SECOND, qm.started_at, qm.completed_at) as time')
            ->join('qm.member', 'user')
            ->andWhere('qm.quiz = :quiz')->setParameter('quiz', $quiz)
            ->andWhere('qm.completed_at IS NOT NULL');
        $qb->orderBy('qm.points', 'DESC');
        $qb->addOrderBy('time', 'ASC');

        $qb->setMaxResults($limit);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param QuizMembers $quizMember
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return int
     */
    public function getMemberPlace(QuizMembers $quizMember): int
    {
        $qb = $this->createQueryBuilder('qm');
        $qb->select('COUNT(qm.id) + 1')
            ->where(
                'qm.points > :points OR (qm.points = :points AND timestampdiff(SECOND, qm.started_at, qm.completed_at) < :time)'
            )
            ->setParameter('points', $quizMember->getPoints())
            ->andWhere('qm.quiz = :quiz')->setParameter('quiz', $quizMember->getQuiz())
            ->setParameter('time',
                $quizMember->getCompletedAt()->getTimestamp() - $quizMember->getStartedAt()->getTimestamp())
            ->andWhere('qm.completed_at IS NOT NULL');

        return $qb->getQuery()->getSingleScalarResult();
    }
}
