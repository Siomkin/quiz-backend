<?php

namespace App\Controller\Api;

use App\Entity\Quiz;
use App\Service\QuizMemberAnswerService;
use App\Service\QuizMemberService;
use App\Service\QuizQuestionService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/api")
 *
 * @IsGranted("ROLE_USER")
 */
class QuizMemberController extends AbstractFOSRestController
{
    private $quizMemberService;
    /**
     * @var QuizMemberAnswerService
     */
    private $quizMemberAnswerService;
    /**
     * @var QuizQuestionService
     */
    private $quizQuestionService;

    /**
     * QuizController constructor.
     *
     * @param QuizMemberService       $quizMemberService
     * @param QuizQuestionService     $quizQuestionService
     * @param QuizMemberAnswerService $quizMemberAnswerService
     */
    public function __construct(
        QuizMemberService $quizMemberService,
        QuizQuestionService $quizQuestionService,
        QuizMemberAnswerService $quizMemberAnswerService)
    {
        $this->quizMemberService = $quizMemberService;
        $this->quizQuestionService = $quizQuestionService;
        $this->quizMemberAnswerService = $quizMemberAnswerService;
    }

    /**
     * Start new quiz.
     *
     * @Rest\Put("/quiz/{id}/passing_start", name="api_quiz_passingStart")
     *
     * @param Quiz                $quiz
     * @param NormalizerInterface $normalizer
     *
     * @throws ORMException
     * @throws ExceptionInterface
     *
     * @return View
     */
    public function start(Quiz $quiz, NormalizerInterface $normalizer): View
    {
        $user = $this->getUser();

        $newQuizMember = $this->quizMemberService->startNew($quiz, $user);

        $newQuizMemberData = $normalizer->normalize($newQuizMember, null, ['groups' => 'startNew']);

        return $this->view($newQuizMemberData);
    }

    /**
     * Get latest user's passing of the certain quiz.
     *
     * @Rest\Get("/quiz/{id}/passing_list", name="api_quiz_passinglist")
     *
     * @Rest\QueryParam(name="_page", requirements="\d+", default="1", description="Page of the overview.")
     *
     * @param Quiz                $quiz
     * @param NormalizerInterface $normalizer
     * @param ParamFetcher        $paramFetcher
     *
     * @throws ExceptionInterface
     *
     * @return View
     */
    public function passingAction(Quiz $quiz, NormalizerInterface $normalizer, ParamFetcher $paramFetcher): View
    {
        $user = $this->getUser();

        $page = $paramFetcher->get('_page', 1);

        $member = $this->quizMemberService->getPassingForUserByQuiz($quiz, $user, $page);

        $memberData = $normalizer->normalize($member, null, ['groups' => 'get']);

        return $this->view(['data' => $memberData, 'pageCount' => $member->getNbPages()]);
    }

    /**
     * @Rest\Get("/quiz/{uuid}/process", name="api_quiz_process")
     *
     * @param string              $uuid
     * @param NormalizerInterface $normalizer
     *
     * @throws ExceptionInterface
     * @throws NotFoundHttpException
     * @throws NonUniqueResultException
     *
     * @return View
     */
    public function processAction(string $uuid, NormalizerInterface $normalizer): View
    {
        $user = $this->getUser();

        $quizMember = $this->quizMemberService->getQuizMemberByUuid($uuid, $user);

        $memberData = $normalizer->normalize($quizMember, null, ['groups' => 'get']);
        $quizData = $normalizer->normalize($quizMember->getQuiz(), null, ['groups' => 'simpleQuiz']);

        $questionsCount = $this->quizQuestionService->getVisibleQuestionsCount($quizMember->getQuiz());

        $answered = $this->quizMemberAnswerService->getAnsweredCount($quizMember);

        return $this->view(
            [
                'quiz' => $quizData,
                'member' => $memberData,
                'questionsCount' => $questionsCount,
                'answered' => $answered,
            ]
        );
    }
}
