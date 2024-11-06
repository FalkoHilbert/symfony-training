<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Volunteer;
use App\Form\VolunteerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/volunteer', name: 'app_volunteer_')]
class VolunteerController extends AbstractController
{
    #[Route('/volunteer/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showVolunteer(Volunteer $volunteer): Response
    {
        return $this->render('volunteer/show_volunteer.html.twig', [
            'volunteer' => $volunteer,
        ]);
    }

    #[Route('/to/{id}', name: 'to_event', requirements: ['id' => '\d+'], methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function index(Event $event, Request $request, EntityManagerInterface $entityManager): Response
    {
        $volunteer = new Volunteer();
        $volunteer->setEvent($event);
        $form = $this->createForm(VolunteerType::class, $volunteer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($volunteer);
            $entityManager->flush();
            return $this->redirectToRoute('app_event_show', [
                'id' => $event->getId(),
            ]);
        }

        return $this->render('volunteer/new_volunteer.html.twig', [
            'form' => $form,
            'event' => $event,
        ]);
    }
}
