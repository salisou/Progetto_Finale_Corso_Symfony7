<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Profile;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use EsperoSoft\Faker\Faker;

class OpenBlogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = new Faker();
        $users = [];

        for ($i = 0; $i < 100; $i++) {
            $user = (new User())->setFirstname($faker->firstname())
                ->setLastname($faker->lastname())
                ->setEmail($faker->email())
                ->setPassword(sha1('0000'))
                ->setCreatedAt($faker->dateTimeImmutable())
                ->setUpdatedAt($faker->dateTimeImmutable());

            $address = (new Address())->setStreet($faker->streetAddress())
                ->setCity($faker->city())
                ->setCountry($faker->country())
                ->setZipCode($faker->postcode())
                ->setCreatedAt($faker->dateTimeImmutable())
                ->setUpdatedAt($faker->dateTimeImmutable());

            $profile = (new Profile())->setPicture($faker->image())
                ->setCoverPicture($faker->image())
                ->setDescription($faker->description(60))
                ->setCreatedAt($faker->dateTimeImmutable())
                ->setUpdatedAt($faker->dateTimeImmutable());


            $user->addAddress($address);
            $user->setProfile(profile: $profile);


            $users[] = $user;


            $manager->persist($user);
            $manager->persist($address);
            $manager->persist($profile);
        }


        // Esercizion
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $category = (new Category())->setName($faker->word())
                ->setDescription($faker->description(60))
                ->setImageUrl($faker->image())
                ->setCreatedAt($faker->dateTimeImmutable())
                ->setUpdatedAt($faker->dateTimeImmutable());

            $categories[] = $category;


            $manager->persist($category);
        }


        $articles = [];
        for ($i = 0; $i < 100; $i++) {
            $article = (new Article())->setTitle($faker->title())
                ->setContent($faker->text(5, 10))
                ->setImageUrl($faker->image())
                ->setCreatedAt($faker->dateTimeImmutable())
                ->setUpdatedAt($faker->dateTimeImmutable())
                ->setAuthor($users[rand(0, count($users) - 1)])
                ->addCategory($categories[rand(0, count($categories) - 1)]);
            $articles[] = $article;


            $manager->persist($article);
        }

        $manager->flush();
    }
}
