<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizMembersRepository")
 */
class QuizMembers
{
    /**
     * Polling for user on my quiz page.
     */
    public const NUM_ITEMS = 20;

    /**
     * Last polling for user on quiz page.
     */
    public const NUM_ITEMS_FOR_QUIZ_PAGE = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"startNew","get"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"get"})
     *
     * @Assert\NotBlank()
     */
    private $quiz;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"get"})
     *
     * @Assert\NotBlank()
     */
    private $member;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Groups({"get"})
     *
     * @Assert\DateTime()
     */
    private $started_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"get"})
     */
    private $completed_at;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     *
     * @Groups({"get"})
     */
    private $attempts;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     *
     * @Groups({"get"})
     */
    private $correctAnswered;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Groups({"get"})
     */
    private $points;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuizMemberAnswers", mappedBy="member")
     */
    private $quizMemberAnswers;

    /**
     * @ORM\Column(type="guid")
     *
     * @Assert\Uuid()
     * @Assert\NotBlank()
     *
     * @Groups({"startNew","get"})
     */
    private $uuid;

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

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function setAttempts(?int $attempts): self
    {
        $this->attempts = $attempts;

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

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}
