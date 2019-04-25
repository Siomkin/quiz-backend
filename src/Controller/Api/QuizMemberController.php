<?php

namespace App\Controller\Api;

use App\Entity\Quiz;
use App\Entity\User;
use App\Service\QuizMemberService;
use Doctrine\ORM\ORMException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * QuizController constructor.
     *
     * @param QuizMemberService $quizMemberService
     */
    public function __construct(QuizMemberService $quizMemberService)
    {
        $this->quizMemberService = $quizMemberService;
    }

    /**
     * Start new quiz.
     *
     * @Route("/quiz/{id}/passing_start", name="api_quiz_passingStart", methods={"PUT"})
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
     * @Route("/quiz/{id}/passing_list", name="api_quiz_passinglist", methods={"GET"})
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

        $passing = $this->quizMemberService->getPassingForUserByQuiz($quiz, $user, $page);

        $passingData = $normalizer->normalize($passing, null, ['groups' => 'get']);

        return $this->view(['data' => $passingData, 'pageCount' => $passing->getNbPages()]);
    }
}
