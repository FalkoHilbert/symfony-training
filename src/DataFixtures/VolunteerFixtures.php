<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Volunteer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class VolunteerFixtures extends Fixture implements DependentFixtureInterface
{
    public const string VOLUNTEER = 'volunteer_';
    public const int NUMBER_OF_VOLUNTEERS = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($organization = 1; $organization <= OrganizationFixtures::NUMBER_OF_ORGANIZATIONS; $organization++) {
            for ($i = 1; $i <= EventFixtures::NUMBER_OF_EVENTS_PER_ORGANIZATION; $i++) {
                for ($j = 1; $j <= self::NUMBER_OF_VOLUNTEERS; $j++) {
                    $volunteer = (new Volunteer())
                        ->setProject($this->getReference(ProjectFixtures::PROJECT_PREFIX . random_int(1, ProjectFixtures::NUMBER_OF_PROJECTS)))
                        ->setEvent($this->getReference(EventFixtures::EVENT_PREFIX . $i . '_' . $organization))
                        ->setStartAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')))
                        ->setEntAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')));
                    $manager->persist($volunteer);
                    $this->addReference(self::VOLUNTEER . $j . '_' . $i . '_' . $organization, $volunteer);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class,
            EventFixtures::class,
        ];
    }

}
