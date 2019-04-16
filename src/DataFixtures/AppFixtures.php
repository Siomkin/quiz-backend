<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->loadUsers($manager);

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$fullname, $password, $email, $roles]) {
            $user = new User();
            $user->setFullname($fullname);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            //$this->addReference($email, $user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            ['Siomkin Alexander', 'password', 'siomkin.alexander@gmail.com', ['ROLE_ADMIN']],
            ['Jane Doe', 'password', 'jane_admin@itransition.com', ['ROLE_ADMIN']],
            ['Tom Doe', 'password', 'tom_user@itransition.com', ['ROLE_USER']],
            ['John Doe', 'password', 'john_user@itransition.com', ['ROLE_USER']],
        ];
    }
}
