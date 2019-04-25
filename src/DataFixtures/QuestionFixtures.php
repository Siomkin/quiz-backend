<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\Common\Persistence\ObjectManager;

class QuestionFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(100, 'Questions', function ($count) use ($manager) {
            $question = new Question();

            $question->setDescription($this->faker->sentence().'?');
            if ($this->faker->boolean(90)) {
                $question->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }

            $question->setVisible($this->faker->boolean(95));

            if ($this->faker->boolean(5)) {
                $question->setDeletedAt($this->faker->dateTimeBetween('-1 days'));
            }

            $num = random_int(3, 6);
            $correctNumber = random_int(0, $num - 1);

            for ($i = 0; $i < $num; ++$i) {
                $comment = new Answer();
                $comment->setDescription(
                    $this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(2, true)
                );

                $comment->setCreatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'));
                $comment->setQuestion($question);

                $isCorrect = $correctNumber === $i;
                $comment->setCorrect($isCorrect);
                if ($isCorrect) {
                    $comment->setDescription('This is correct answer.');
                }
                $manager->persist($comment);
            }

            return $question;
        });

        $manager->flush();
    }
}
