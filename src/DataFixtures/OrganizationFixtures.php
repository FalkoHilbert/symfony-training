<?php

namespace App\DataFixtures;

use App\Entity\Organization;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OrganizationFixtures extends Fixture implements DependentFixtureInterface
{
    public const int NUMBER_OF_ORGANIZATIONS = 10;
    public const string ORGANIZATION_PREFIX = 'organization_';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 1; $i <= self::NUMBER_OF_ORGANIZATIONS; $i++) {
            $organization = (new Organization())
                ->setName($faker->company)
                ->setPresentation($faker->realText(100))
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now')));
            for ($j = 1; $j <= EventFixtures::NUMBER_OF_EVENTS_PER_ORGANIZATION; $j++) {
                $organization->addEvent($this->getReference(EventFixtures::EVENT_PREFIX . $j . '_'. $i) );
            }
            for ($j = 1; $j <= EventFixtures::NUMBER_OF_EVENTS_PER_ORGANIZATION; $j++) {
                $organization->addProject($this->getReference(ProjectFixtures::PROJECT_PREFIX . random_int(1, ProjectFixtures::NUMBER_OF_PROJECTS)));
            }
            $manager->persist($organization);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EventFixtures::class,
        ];
    }
}
