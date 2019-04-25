<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(3, 'admin_users', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@it.com', $i));
            $user->setName($this->faker->firstName);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setEnabled($this->faker->boolean(95));

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'pass'
            ));

            return $user;
        });

        $this->createMany(100, 'Users', function ($i) use ($manager) {
            $user = new User();
            $user->setEmail(sprintf('user%d@it.com', $i));
            $user->setName($this->faker->firstName);
            $user->setEnabled($this->faker->boolean(75));
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'pass'
            ));

//            $apiToken1 = new ApiToken($user);
//            $apiToken2 = new ApiToken($user);
//            $manager->persist($apiToken1);
//            $manager->persist($apiToken2);

            return $user;
        });

        $manager->flush();
    }
}
