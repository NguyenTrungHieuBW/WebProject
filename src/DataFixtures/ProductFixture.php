<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i=1; $i<=20; $i++)
        {
            $product = new Product();
            $product->setName("Product $i");
            $product->setDescription("");
            $product->setImage("pencilcase.png");
            $product->setPrice(99);
            $manager->persist($product);
        } 

        $manager->flush();
    }
}
