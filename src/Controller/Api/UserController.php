<?php

namespace App\Controller\Api;

use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
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
     * @Route("/users/{id}", name="api_user_show", methods={"GET"})
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
