<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Repository\QuestionRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class QuizQuestionService
{
    /**
     * @var QuestionRepository
     */
    private $quizQuestionRepository;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * QuizController constructor.
     *
     * @param QuestionRepository     $quizQuestionRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(QuestionRepository $quizQuestionRepository, EntityManagerInterface $entityManager)
    {
        $this->quizQuestionRepository = $quizQuestionRepository;
        $this->em = $entityManager;
    }

    /**
     * @param int $id
     *
     * @return Question
     */
    public function getQuestion(int $id): Question
    {
        return $this->quizQuestionRepository->find($id);
    }

    /**
     * @param Quiz $quiz
     *
     * @throws NonUniqueResultException
     *
     * @return int
     */
    public function getVisibleQuestionsCount(Quiz $quiz)
    {
        return $this->quizQuestionRepository->getQuestionsCount($quiz, true);
    }

    /**
     * @param string $uuid
     *
     * @throws DBALException
     *
     * @return array
     */
    public function getNotAnsweredQuestionByMember(string $uuid): ?array
    {
        return $this->quizQuestionRepository->getNotAnsweredQuestionByMember($uuid);
    }
}
