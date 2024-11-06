<?php

namespace App\Consume;

use App\Search\EventSearchInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly final class EventsApiConsumer implements EventSearchInterface
{
    public function __construct(
        private HttpClientInterface $eventsClient
    )
    {
    }

    /** @inheritDoc */
    public function searchByName(?string $name = null): array
    {
        return $this->eventsClient->request('GET', '/events', [
            'query' => [
                'name' => $name,
            ]
        ])->toArray()['hydra:member'];

    }

    /** @inheritDoc */
    public function searchByDate(?\DateTimeImmutable $start = null, ?\DateTimeImmutable $end = null): array
    {
        // TODO: Implement searchByDate() method.
        return [];
    }

    /** @inheritDoc */
    public function searchByFilter(array $filters): array
    {
        return $this->eventsClient->request('GET', '/events', [
            'query' => [
                'name' => $filters['name'] ?? null,
            ]
        ])->toArray()['hydra:member'];
    }
}