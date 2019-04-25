<?php

namespace App\Service;

use App\Entity\Quiz;
use App\Repository\QuizRepository;
use Pagerfanta\Pagerfanta;

class QuizService
{
    /**
     * @var QuizRepository
     */
    private $quizRepository;

    /**
     * QuizController constructor.
     *
     * @param QuizRepository $quizRepository
     */
    public function __construct(QuizRepository $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    /**
     * @param int $id
     *
     * @return Quiz|null
     */
    public function getQuiz(int $id)
    {
        return $this->quizRepository->find($id);
    }

    /**
     * @param int   $page
     * @param array $query
     * @param bool  $visible
     *
     * @return Pagerfanta
     */
    public function getQuizzes($page = 1, $query = [], $visible = true): Pagerfanta
    {
        return $this->quizRepository->selectAll($page, $query, $visible);
    }
}
