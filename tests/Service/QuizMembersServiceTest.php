<?php

namespace App\Tests\Servises;

use App\Entity\Quiz;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class QuizMembersServiceTest extends TestCase
{
    public function testStartNewPassing()
    {
        $user = $this->createMock(User::class);
        $quiz = $this->createMock(Quiz::class);

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGettingPassingForUserByQuiz()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
