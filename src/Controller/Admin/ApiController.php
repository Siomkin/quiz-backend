<?php

namespace App\Controller\Admin;

use App\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/getInfo/{id}", name="admin_api")
     */
    public function getInfo(Quiz $quiz)
    {
        return $this->json(['Hello', 'qiuz' => $quiz->getId()]);
    }
}
