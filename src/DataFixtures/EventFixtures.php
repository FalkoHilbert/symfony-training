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
        for ($eventIndex = 1; $eventIndex <= self::NUMBER_OF_EVENTS_PER_ORGANIZATION; $eventIndex++) {
            $startDate = DateTimeImmutable::createFromMutable($faker->dateTimeBetween('today', '+2 month'));
            $event = (new Event())
                ->setCreatedBy($this->getReference(UserFixtures::USER_PREFIX.'website'))
                ->setName($faker->words(3, true))
                ->setDescription($faker->realText(200))
                ->setIsAccessible($faker->boolean())
                ->setStartDate($startDate)
                ->setEndDate(DateTimeImmutable::createFromMutable($faker->dateTimeBetween($startDate->format('c'), '+4 month')))
                ->setProject(
                    $this->getReference(ProjectFixtures::PROJECT_PREFIX . random_int(1, ProjectFixtures::NUMBER_OF_PROJECTS))
                )
            ;
            for ($organizationIndex = 1; $organizationIndex <= OrganizationFixtures::NUMBER_OF_ORGANIZATIONS ; $organizationIndex++) {
                $event->addOrganization($this->getReference(OrganizationFixtures::ORGANIZATION_PREFIX . $organizationIndex) );
            }
            $manager->persist($event);
            $this->addReference(self::EVENT_PREFIX . $eventIndex, $event);
        }


        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            OrganizationFixtures::class,
            ProjectFixtures::class,
        ];
    }
}
