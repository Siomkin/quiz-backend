<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UserConfirmation
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=30, max=30)
     */
    public $confirmationToken;
}
