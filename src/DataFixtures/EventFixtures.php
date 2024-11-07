<?php

namespace App\DataFixtures;

use App\Entity\Event;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Random\RandomException;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public const string EVENT_PREFIX = 'event_';
    public const int NUMBER_OF_EVENTS_PER_ORGANIZATION = 10;

    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($organizationIndex = 1; $organizationIndex <= OrganizationFixtures::NUMBER_OF_ORGANIZATIONS ; $organizationIndex++) {
            for ($eventIndex = 1; $eventIndex <= self::NUMBER_OF_EVENTS_PER_ORGANIZATION; $eventIndex++) {
                $event = (new Event())
                    ->setName($faker->words(3, true))
                    ->setDescription($faker->realText(200))
                    ->setIsAccessible($faker->boolean())
                    ->setStartDate(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 month', '+1 month')))
                    ->setEndDate(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+1 month', '+2 month')))
                    ->setProject(
                        $this->getReference(ProjectFixtures::PROJECT_PREFIX . random_int(1, ProjectFixtures::NUMBER_OF_PROJECTS))
                    )
                ;
                $manager->persist($event);
                $this->addReference(self::EVENT_PREFIX . $eventIndex . '_'. $organizationIndex, $event);
            }
        }

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class,
        ];
    }
}
