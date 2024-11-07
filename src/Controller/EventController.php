<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Search\DatabaseEventSearch;
use App\Search\EventSearchInterface;
use App\Security\Voter\CreatedVoter;
use App\Security\Voter\EditVoter;
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
            ->setIsAccessible(true)
            ->setStartDate(new DateTimeImmutable($start))
            ->setEndDate(new DateTimeImmutable($end));

        $entityManager->persist($event);
        $entityManager->flush();

        return new Response('Event created');
    }

    #[Route('/', name: 'list', methods: [Request::METHOD_GET])]
    public function listEvents(
        #[MapQueryParameter] ?string $start,
        #[MapQueryParameter] ?string $end,
        #[MapQueryParameter] ?string $name,
        DatabaseEventSearch $search
    ): Response{
        $startDate = !empty($start) ? new DateTimeImmutable($start) : null;
        $endDate = !empty($end) ? new DateTimeImmutable($end) : null;
        $events = $search->searchByFilter(
            [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'name' => $name,
            ]
        );
        return $this->render('event/list.html.twig',[
                'events' => $events
            ]
        );
    }

    #[Route('/remote', name: 'remote', methods: [Request::METHOD_GET])]
    public function remoteEvents(
        #[MapQueryParameter] ?string $name,
        EventSearchInterface $search
    ): Response{
        $events = $search->searchByName($name);
        return $this->render('event/remote.html.twig',[
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
    #[Route('/edit/{id}', name: 'edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function newEventForm(
        Request $request,
        EntityManagerInterface $entityManager,
        Event $event = null
    ): Response
    {
        if($event instanceof Event )
        {
            $this->denyAccessUnlessGranted(EditVoter::EDIT, $event);
        }
        $event = $event ?? new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid( ))
        {
            $event->setCreatedBy($this->getUser());
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('app_event_show', [
                'id' => $event->getId()
            ]);
        }
        return $this->render('event/edit.html.twig',[
            'form' => $form,
            'event' => $event
        ]);
    }
}
