<?php

namespace App\Service;

use App\Entity\Quiz;
use App\Entity\QuizMembers;
use App\Entity\User;
use App\Repository\QuizMembersRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\Uuid;

class QuizMemberService
{
    /**
     * @var QuizMembersRepository
     */
    private $quizMembersRepository;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * QuizController constructor.
     *
     * @param QuizMembersRepository  $quizMembersRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(QuizMembersRepository $quizMembersRepository, EntityManagerInterface $entityManager)
    {
        $this->quizMembersRepository = $quizMembersRepository;
        $this->em = $entityManager;
    }

    /**
     * Get quiz passing by user.
     *
     * @param int $id
     *
     * @return QuizMembers
     */
    public function getQuizMember(int $id): QuizMembers
    {
        return $this->quizMembersRepository->find($id);
    }

    /**
     * Start new quiz for user.
     *
     * @param Quiz $quiz
     * @param User $user
     *
     * @throws ORMException
     * @throws \Exception
     *
     * @return QuizMembers
     */
    public function startNew(Quiz $quiz, User $user): QuizMembers
    {
        $newMember = new QuizMembers();
        $newMember->setMember($user);
        $newMember->setQuiz($quiz);
        $newMember->setUuid(Uuid::uuid4());

        $this->em->persist($newMember);
        $this->em->flush();

        return $newMember;
    }

    /**
     * Get data about last passing for the user on quiz page.
     *
     * @param Quiz $quiz
     * @param User $user
     * @param int  $page
     *
     * @return Pagerfanta
     */
    public function getPassingForUserByQuiz(Quiz $quiz, User $user, $page = 1): Pagerfanta
    {
        $result = $this->quizMembersRepository->selectPassingForUserByQuiz($quiz, $user, $page);

        return $result;
    }
}
