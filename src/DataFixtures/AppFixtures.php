<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 100; $i++) {
            $product = new Product();
            $product->setName($faker->randomElement([
                'iPhone X', 'iPhone XS', 'iPhone XR', 'Galaxy S9', 'Galaxy S10'
            ]));
            $product->setDescription($faker->text(300));
            $product->setPrice($faker->randomNumber(5) * 100);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
