<?php

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\QuizMemberAnswers;
use App\Entity\QuizMembers;
use App\Entity\User;
use App\Repository\QuizMemberAnswersRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class QuizMemberAnswerService
{
    private $quizMemberAnswersRepository;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * QuizController constructor.
     *
     * @param QuizMemberAnswersRepository $quizMemberAnswersRepository
     * @param EntityManagerInterface      $entityManager
     */
    public function __construct(QuizMemberAnswersRepository $quizMemberAnswersRepository, EntityManagerInterface $entityManager)
    {
        $this->quizMemberAnswersRepository = $quizMemberAnswersRepository;
        $this->em = $entityManager;
    }

    /**
     * Save member answer.
     *
     * @param QuizMembers $user
     * @param int         $questionId
     * @param int         $answerId
     * @param bool        $isCorrect
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return QuizMemberAnswers
     */
    public function insertAnswer(QuizMembers $user, int $questionId, int $answerId, bool $isCorrect): QuizMemberAnswers
    {
        $question = $this->em->getRepository(Question::class)->findOneBy(['id' => $questionId]);
        $answer = $this->em->getRepository(Answer::class)->findOneBy(['id' => $answerId]);
        $memberAnswers = $this->em->getRepository(QuizMemberAnswers::class)->findOneBy(['member' => $user, 'question' => $question]);

        if (null === $memberAnswers) {
            $memberAnswers = new QuizMemberAnswers();
            $memberAnswers->setMember($user);
            $memberAnswers->setQuestion($question);
        }

        $memberAnswers->setAnswer($answer);
        $memberAnswers->setCorrect($isCorrect);

        $this->em->persist($memberAnswers);
        $this->em->flush();

        return $memberAnswers;
    }

    /**
     * @param QuizMembers $member
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return int
     */
    public function getAnsweredCount(QuizMembers $member): int
    {
        return $this->quizMemberAnswersRepository->getAnsweredCount($member);
    }

    /**
     * @param QuizMembers $member
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return int
     */
    public function getCorrectAnsweredCount(QuizMembers $member): int
    {
        return $this->quizMemberAnswersRepository->getAnsweredCount($member, true);
    }
}
