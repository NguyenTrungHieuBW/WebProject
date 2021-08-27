<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CartFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*
        $cart = new Cart();
        $cart->setUserid(2);
        $cart->setProductid("test");
        $manager->persist($cart);
        $manager->flush();
        */
    }
}
