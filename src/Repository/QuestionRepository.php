<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Repository\Traits\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\NonUniqueResultException;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    use Paginator;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function selectAll($page = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('q')
            ->select('q')
            ->orderBy('q.id', 'DESC');

        return $this->createPaginator($qb->getQuery(), $page, Question::NUM_ITEMS);
    }

    /**
     * @param Quiz $quiz
     * @param null $visible
     *
     * @throws NonUniqueResultException
     *
     * @return int
     */
    public function getQuestionsCount(Quiz $quiz, $visible = null): int
    {
        $qb = $this->createQueryBuilder('q');
        $qb->select('COUNT(q.id)')
            ->innerJoin('q.quizzes', 'quizzes')
            ->andWhere('quizzes = :quiz')->setParameter('quiz', $quiz);

        if (null !== $visible) {
            $qb->andWhere('q.visible = :visible')->setParameter('visible', $visible);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $uuid
     *
     * @throws DBALException
     *
     * @return array
     */
    public function getNotAnsweredQuestionByMember($uuid): ?array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT
                question.id,
                question.description,
                question.visible,
                quiz_member_answers.id as member_answers_id  
            FROM
                question
                INNER JOIN quiz_question ON quiz_question.question_id = question.id
                INNER JOIN quiz ON quiz_question.quiz_id = quiz.id
                INNER JOIN quiz_members ON quiz_members.quiz_id = quiz.id
                LEFT JOIN quiz_member_answers ON quiz_member_answers.question_id = question.id AND quiz_member_answers.member_id = quiz_members.id 
            WHERE
                quiz_members.uuid = :uuid AND question.visible = 1
            HAVING
                member_answers_id IS NULL
            LIMIT 1
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['uuid' => $uuid]);

        $result = $stmt->fetch();

        return false !== $result ? $result : null;
    }
}
