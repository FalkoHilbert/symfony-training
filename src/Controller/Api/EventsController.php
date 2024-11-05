<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Repository\EventRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/events', format: 'json' )]
class EventsController extends AbstractController
{
    #[Route('/', name: 'app_events_api.list' , methods: [Request::METHOD_GET])]
    public function list(
        #[MapQueryParameter] ?string $start,
        #[MapQueryParameter] ?string $end,
        EventRepository $eventRepository
    ): JsonResponse
    {
        $startDate = !empty($start) ? new DateTimeImmutable($start) : null;
        $endDate = !empty($end) ? new DateTimeImmutable($end) : null;

        return $this->json($eventRepository->findAllByDates($startDate, $endDate));
    }

    #[Route('/{id}', name: 'app_events_api.get' , methods: [Request::METHOD_GET])]
    public function get(
        Event $event,
        EventRepository $eventRepository
    ): JsonResponse
    {
        return $this->json($event);
    }

}
