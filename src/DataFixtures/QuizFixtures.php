<?php

namespace App\DataFixtures;

use App\Entity\Quiz;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class QuizFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(100, 'Quiz', function ($count) use ($manager) {
            $quiz = new Quiz();

            $quiz->setTitle($this->faker->sentence());
            $quiz->setAuthor($this->getRandomReference('admin_users'));

            if ($this->faker->boolean(90)) {
                $quiz->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }

            $visible = $this->faker->boolean(95) ? true : false;
            $quiz->setVisible($visible);

            if ($this->faker->boolean(5)) {
                $quiz->setDeletedAt($this->faker->dateTimeBetween('-1 days'));
            }

            $questions = $this->getRandomReferences('Questions', $this->faker->numberBetween(5, 10));
            foreach ($questions as $question) {
                $quiz->addQuestion($question);
            }

            return $quiz;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [QuestionFixtures::class, UserFixture::class];
    }
}
