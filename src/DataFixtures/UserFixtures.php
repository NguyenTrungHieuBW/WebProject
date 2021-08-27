<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $hasher;
    //Cai nay la de encode password
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager)
    {
        $admin=new User();
        $admin->setUsername("admin");
        $admin->setPassword($this->hasher->hashPassword($admin,"123"));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $admin=new User();
        $admin->setUsername("user");
        $admin->setPassword($this->hasher->hashPassword($admin,"123"));
        $admin->setRoles([]);
        $manager->persist($admin);

        $manager->flush();
    }
}
