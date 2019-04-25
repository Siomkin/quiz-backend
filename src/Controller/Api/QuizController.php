<?php

namespace App\Controller\Api;

use App\Service\QuizService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/api")
 */
class QuizController extends AbstractFOSRestController
{
    private $quizService;

    /**
     * QuizController constructor.
     *
     * @param QuizService $quizService
     */
    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     *  Get a list of quizzes.
     *
     * @Route("/quizzes", name="api_quizzes", methods={"GET"})
     *
     * @Rest\QueryParam(name="_page", requirements="\d+", default="1", description="Page of the overview.")
     *
     * @param ParamFetcher        $paramFetcher
     * @param NormalizerInterface $normalizer
     *
     * @throws ExceptionInterface
     *
     * @return View
     */
    public function quizzesAction(ParamFetcher $paramFetcher, NormalizerInterface $normalizer): View
    {
        $page = $paramFetcher->get('_page', 1);

        $quizzes = $this->quizService->getQuizzes($page);

        $quizzesN = $normalizer->normalize($quizzes, null, ['groups' => 'simpleQuiz']);

        return $this->view(['data' => $quizzesN, 'pageCount' => $quizzes->getNbPages()]);
    }

    /**
     * Get a quiz information.
     *
     * @Route("/quizzes/{id}", name="api_quiz_item", methods={"GET"})
     *
     * @param int                 $id
     * @param NormalizerInterface $normalizer
     *
     * @throws ExceptionInterface
     *
     * @return View
     */
    public function quizAction(int $id, NormalizerInterface $normalizer): View
    {
        $quiz = $this->quizService->getQuiz($id);

        $quiz = $normalizer->normalize($quiz, null, ['groups' => 'simpleQuiz']);

        return $this->view($quiz);
    }
}
