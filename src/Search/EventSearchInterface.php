<?php

namespace App\Search;

use App\Entity\Event;

interface EventSearchInterface
{
    /** @return Event[] */
    public function searchByName(?string $name = null): array;
    /** @return Event[] */
    public function searchByDate(?\DateTimeImmutable $start = null, ?\DateTimeImmutable $end = null): array;
    /** @return Event[] */
    public function searchByFilter(array $filters): array;
}