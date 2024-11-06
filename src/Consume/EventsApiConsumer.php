<?php

namespace App\Consume;

use App\Search\EventSearchInterface;
use SensitiveParameter;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class EventsApiConsumer implements EventSearchInterface
{
    public function __construct(
        #[SensitiveParameter]
        #[Autowire(env: 'EVENTS_API_URL')]
        private string $apiKey,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function searchByName(?string $name = null): array
    {
        // TODO: Implement searchByName() method.
    }

    /**
     * @inheritDoc
     */
    public function searchByDate(?\DateTimeImmutable $start = null, ?\DateTimeImmutable $end = null): array
    {
        // TODO: Implement searchByDate() method.
    }

    /**
     * @inheritDoc
     */
    public function searchByFilter(array $filters): array
    {
        // TODO: Implement searchByFilter() method.
    }
}