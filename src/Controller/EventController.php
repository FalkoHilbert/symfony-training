<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/events', name: 'app_event_')]
class EventController extends AbstractController
{
    /**
     * @throws \DateMalformedStringException
     */
    #[Route('/new/{name}/{start}/{end}', name: 'new_by_path', requirements: [
        'start' => Requirement::DATE_YMD,
        'end' => Requirement::DATE_YMD
    ], methods: [Request::METHOD_GET])]
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

    #[Route('/', name: 'list', methods: [Request::METHOD_GET])]
    public function listEvents(
        #[MapQueryParameter] ?string $start,
        #[MapQueryParameter] ?string $end,
        EventRepository $eventRepository
    ): Response{
        $startDate = !empty($start) ? new DateTimeImmutable($start) : null;
        $endDate = !empty($end) ? new DateTimeImmutable($end) : null;

        $events = (!empty($startDate) || !empty($endDate ) ) ?
            $eventRepository->findAllByDates($startDate, $endDate) :
            $eventRepository->findAll();

        return $this->render('event/list.html.twig',[
                'events' => $events
            ]
        );
    }

    #[Route('/show/{id}', name: 'show', methods: [Request::METHOD_GET])]
    public function showEvent(
        Event $event,
    ): Response{
        return $this->render('event/show.html.twig',[
                'event' => $event
            ]
        );
    }

    #[Route('/new', name: 'new', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function newEventForm(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid( ))
        {
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('app_event_show', [
                'id' => $event->getId()
            ]);
        }
        return $this->render('event/new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function editEventForm(Event $event, Request $request): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid( )) {
            return $this->redirectToRoute('app_event_show', [
                'id' => $event->getId()
            ]);
        }
        return $this->render('event/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
