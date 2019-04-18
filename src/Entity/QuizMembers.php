<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizMembersRepository")
 */
class QuizMembers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $member;

    /**
     * @ORM\Column(type="datetime")
     */
    private $started_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $completed_at;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $questionsCount;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $correctAnswered;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $points;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuizMemberAnswers", mappedBy="member")
     */
    private $quizMemberAnswers;

    public function __construct()
    {
        $this->quizMemberAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getMember(): ?User
    {
        return $this->member;
    }

    public function setMember(?User $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->started_at;
    }

    public function setStartedAt(\DateTimeInterface $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeInterface
    {
        return $this->completed_at;
    }

    public function setCompletedAt(?\DateTimeInterface $completed_at): self
    {
        $this->completed_at = $completed_at;

        return $this;
    }

    public function getQuestionsCount(): ?int
    {
        return $this->questionsCount;
    }

    public function setQuestionsCount(?int $questionsCount): self
    {
        $this->questionsCount = $questionsCount;

        return $this;
    }

    public function getCorrectAnswered(): ?int
    {
        return $this->correctAnswered;
    }

    public function setCorrectAnswered(?int $correctAnswered): self
    {
        $this->correctAnswered = $correctAnswered;

        return $this;
    }

    public function getPoints(): ?float
    {
        return $this->points;
    }

    public function setPoints(?float $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return Collection|QuizMemberAnswers[]
     */
    public function getQuizMemberAnswers(): Collection
    {
        return $this->quizMemberAnswers;
    }

    public function addQuizMemberAnswer(QuizMemberAnswers $quizMemberAnswer): self
    {
        if (!$this->quizMemberAnswers->contains($quizMemberAnswer)) {
            $this->quizMemberAnswers[] = $quizMemberAnswer;
            $quizMemberAnswer->setMember($this);
        }

        return $this;
    }

    public function removeQuizMemberAnswer(QuizMemberAnswers $quizMemberAnswer): self
    {
        if ($this->quizMemberAnswers->contains($quizMemberAnswer)) {
            $this->quizMemberAnswers->removeElement($quizMemberAnswer);
            // set the owning side to null (unless already changed)
            if ($quizMemberAnswer->getMember() === $this) {
                $quizMemberAnswer->setMember(null);
            }
        }

        return $this;
    }
}
