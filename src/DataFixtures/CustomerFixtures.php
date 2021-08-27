<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i<=10; $i++)
        {
            $customer = new Customer();
            $customer->setName("Test $i");
            $customer->setFullName("Nguyen Trung Hieu");
            $customer->setAddress("test");
            $customer->setPhone("test");
            $customer->setEmail("test");
            $customer->setPassword("test");
            $manager->persist($customer);
        } 
        $manager->flush();
    }
}
