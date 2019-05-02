<?php

namespace App\Controller\Api;

use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/api")
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/users/{id}", name="api_user_show")
     *
     * @param User                $user
     * @param NormalizerInterface $normalizer
     *
     * @throws ExceptionInterface
     *
     * @return View
     */
    public function show(User $user, NormalizerInterface $normalizer): View
    {
        $userProfileData = $normalizer->normalize($user, null, ['groups' => 'profile']);

        return $this->view($userProfileData);
    }
}
