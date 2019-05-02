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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Get quiz passing by user.
     *
     * @param string $uuid
     * @param User   $user
     *
     * @throws NotFoundHttpException
     *
     * @return QuizMembers|null
     */
    public function getQuizMemberByUuid(string $uuid, User $user): ?QuizMembers
    {
        $member = $this->quizMembersRepository->findOneBy(['uuid' => $uuid, 'member' => $user]);

        if (null === $member) {
            throw new NotFoundHttpException('Quiz member not found');
        }

        return $member;
    }

    /**
     * @param QuizMembers $member
     *
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return QuizMembers
     */
    public function increaseAttempts(QuizMembers $member): QuizMembers
    {
        $member->setAttempts((int) $member->getAttempts() + 1);

        $this->em->persist($member);
        $this->em->flush();

        return $member;
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

    public function completeQuiz(QuizMembers $quizMember, int $correctAnswered): QuizMembers
    {
        $quizMember->setCompletedAt(new \DateTime());

        $quizMember->setCorrectAnswered($correctAnswered);

        $quizMember->setPoints(round($correctAnswered / $quizMember->getAttempts() * 100));

        $this->em->persist($quizMember);
        $this->em->flush();

        return $quizMember;
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
