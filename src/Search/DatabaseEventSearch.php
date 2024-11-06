<?php

namespace App\Search;

use App\Entity\Event;
use App\Repository\EventRepository;

readonly final class DatabaseEventSearch implements EventSearchInterface
{
    public function __construct(
        private EventRepository $eventRepository
    )
    { }

    /** @return Event[] */
    public function searchByName(?string $name = null): array
    {
        return $this->eventRepository->findLikeName($name);

    }

    /** @return Event[] */
    public function searchByDate(?\DateTimeImmutable $start = null, ?\DateTimeImmutable $end = null): array
    {
        return $this->eventRepository->findAllByDates($start, $end);
    }

    /** @return Event[] */
    public function searchByFilter(array $filters): array
    {
        return $this->eventRepository->findByFilter($filters);
    }
}