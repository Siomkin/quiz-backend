<?php

namespace App\Service;

use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class QuizQuestionAnswersService
{
    private $quizQuestionAnswersRepository;

    private $em;

    /**
     * QuizController constructor.
     *
     * @param AnswerRepository       $quizQuestionAnswersRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(AnswerRepository $quizQuestionAnswersRepository, EntityManagerInterface $entityManager)
    {
        $this->quizQuestionAnswersRepository = $quizQuestionAnswersRepository;
        $this->em = $entityManager;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getVariantsForQuestion(int $id): array
    {
        return $this->quizQuestionAnswersRepository->getVariantsForQuestion($id);
    }

    /**
     * @param int $questionId
     * @param int $selectedAnswerId
     *
     * @throws NonUniqueResultException
     *
     * @return bool
     */
    public function isAnswerRight(int $questionId, int $selectedAnswerId): bool
    {
        $rightAnswerId = $this->quizQuestionAnswersRepository->getRightAnswerId($questionId);

        return $rightAnswerId === $selectedAnswerId;
    }
}
