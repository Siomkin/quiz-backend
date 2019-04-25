<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Admin\ApiController;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post"={
 *             "path"="/users/confirm"
 *         }
 *     },
 *     itemOperations={
 *     "getInfo"={
 *         "method"="GET",
 *         "path"="/getInfo/{id}",
 *         "controller"=ApiController::class,
 *     }
 *     },
 *
 * )
 */
class UserConfirmation
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=30, max=30)
     */
    public $confirmationToken;
}
