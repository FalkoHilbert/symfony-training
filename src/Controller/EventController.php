<?php

namespace App\Controller;

use App\Entity\Event;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class EventController extends AbstractController
{
    /**
     * @throws \DateMalformedStringException
     */
    #[Route('/event/{name}/{start}/{end}', name: 'app_event_new', requirements: [
        'start' => Requirement::DATE_YMD,
        'end' => Requirement::DATE_YMD
    ])]
    public function newEvent(
        string $name,
        string $start,
        string $end,
        EntityManagerInterface $entityManager
    ): Response
    {
        $faker = Factory::create();
        $event = (new Event())
            ->setName($name)
            ->setDescription($faker->realText(100))
            ->setAccessible(true)
            ->setStartAt(new DateTimeImmutable($start))
            ->setEndAt(new DateTimeImmutable($end));

        $entityManager->persist($event);
        $entityManager->flush();

        return new Response('Event created');
    }
}
