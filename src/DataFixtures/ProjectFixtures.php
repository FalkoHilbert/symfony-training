<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProjectFixtures extends Fixture
{
    public const string PROJECT_PREFIX = 'project_';
    public const int NUMBER_OF_PROJECTS = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 1; $i <= self::NUMBER_OF_PROJECTS; $i++) {
            $project = (new Project())
                ->setName($faker->words(3, true))
                ->setSummary($faker->realText(200))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now')))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now')))
            ;
            $this->addReference(self::PROJECT_PREFIX . $i, $project);
            $manager->persist($project);
        }

        $manager->flush();
    }
}
