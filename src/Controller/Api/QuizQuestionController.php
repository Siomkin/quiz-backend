<?php

namespace App\Controller\Api;

use App\Service\QuizMemberAnswerService;
use App\Service\QuizMemberService;
use App\Service\QuizQuestionAnswersService;
use App\Service\QuizQuestionService;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\NonUniqueResultException;
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
class QuizQuestionController extends AbstractFOSRestController
{
    private $quizMemberService;

    private $quizMemberAnswerService;

    private $quizQuestionService;

    private $quizQuestionAnswersService;

    /**
     * QuizController constructor.
     *
     * @param QuizMemberService          $quizMemberService
     * @param QuizMemberAnswerService    $quizMemberAnswerService
     * @param QuizQuestionService        $quizQuestionService
     * @param QuizQuestionAnswersService $quizQuestionAnswersService
     */
    public function __construct(
        QuizMemberService $quizMemberService,
        QuizMemberAnswerService $quizMemberAnswerService,
        QuizQuestionService $quizQuestionService,
        QuizQuestionAnswersService $quizQuestionAnswersService
    ) {
        $this->quizMemberService = $quizMemberService;
        $this->quizMemberAnswerService = $quizMemberAnswerService;
        $this->quizQuestionService = $quizQuestionService;
        $this->quizQuestionAnswersService = $quizQuestionAnswersService;
    }

    /**
     * @Rest\Get("/quiz/{uuid}/question", name="api_quiz_process_question")
     *
     * @param string $uuid
     *
     * @throws NonUniqueResultException
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return View
     */
    public function processQuestionAction(string $uuid): View
    {
        $user = $this->getUser();
        $member = $this->quizMemberService->getQuizMemberByUuid($uuid, $user);

        $question = $this->quizQuestionService->getNotAnsweredQuestionByMember($uuid);

        if (null === $question) {
            throw new NotFoundHttpException('No unanswered question exist');
        }

        $answers = $this->quizQuestionAnswersService->getVariantsForQuestion($question['id']);

        $answered = $this->quizMemberAnswerService->getCorrectAnsweredCount($member);

        return $this->view(
            [
                'completed' => false,
                'question' => $question,
                'answers' => $answers,
                'answered' => $answered,
            ]
        );
    }

    /**
     * @Rest\Post("/quiz/{uuid}/question", name="api_quiz_check_answer")
     *
     * @Rest\RequestParam(name="id", requirements="\d+", description="Question Id")
     * @Rest\RequestParam(name="selected", requirements="\d+", description="Selected answer.")
     *
     * @param string              $uuid
     * @param ParamFetcher        $paramFetcher
     * @param NormalizerInterface $normalizer
     *
     * @throws DBALException
     * @throws ExceptionInterface
     * @throws NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return View
     */
    public function checkAnswerAction(string $uuid, ParamFetcher $paramFetcher, NormalizerInterface $normalizer): View
    {
        $changedMemberData = null;

        $user = $this->getUser();
        $quizMember = $this->quizMemberService->getQuizMemberByUuid($uuid, $user);

        $questionId = $paramFetcher->get('id');
        $selectedAnswerId = $paramFetcher->get('selected');

        $isRight = $this->quizQuestionAnswersService->isAnswerRight($questionId, $selectedAnswerId);

        $this->quizMemberService->increaseAttempts($quizMember);
        $this->quizMemberAnswerService->insertAnswer($quizMember, $questionId, $selectedAnswerId, $isRight);

        if ($isRight) {
            // Check whether next not answered question exist
            $nextQuestion = $this->quizQuestionService->getNotAnsweredQuestionByMember($uuid);

            // Complete quiz polling
            if (null === $nextQuestion) {
                $correctAnswered = $this->quizMemberAnswerService->getCorrectAnsweredCount($quizMember);
                $quizMember = $this->quizMemberService->completeQuiz($quizMember, $correctAnswered);

                $changedMemberData = $normalizer->normalize($quizMember, null, ['groups' => 'get']);
            }
        }

        return $this->view(
            [
                'right' => $isRight,
                'member' => $changedMemberData,
            ]
        );
    }
}
