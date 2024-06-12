<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addSeries(50, $manager);

    }

    private function addSeries(int $number, ObjectManager $manager){

        $faker = Factory::create('fr_FR');

        for($i = 0; $i < $number; $i++){
            $serie = new Serie();
            $serie
                ->setName("Serie $i")
                ->setBackdrop("backdrop.png $i")
                ->setDateCreated($faker->dateTimeBetween("-2 year", "-6 months"))
                ->setGenres($faker->randomElement(["Fantasy", "Polar", "Western", "Action", "Aventure"]))
                ->setFirstAirDate($faker->dateTimeBetween("-2 year", "-1 year"));

            $serie
                ->setLastAirDate($faker->dateTimeBetween($serie->getFirstAirDate(), "now"))
                ->setPopularity($faker->numberBetween(0, 1000))
                ->setPoster("poster.png")
                ->setStatus($faker->randomElement(["returning", "canceled", "ended"]))
                ->setTmdbId($faker->randomDigit())
                ->setVote($faker->numberBetween(1, 9));

            $manager->persist($serie);
        }

        $manager->flush();
    }





}
