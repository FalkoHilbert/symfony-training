<?php

namespace App\Search;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[AsDecorator(decorates: EventSearchInterface::class)]
class CacheableEventSearch implements EventSearchInterface
{
    public function __construct(
        private EventSearchInterface $eventSearch,
        private CacheInterface $cache
    )
    { }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function searchByName(?string $name = null): array
    {
        return $this->cache->get(md5($name), function (ItemInterface $item) use ($name) {
            $item->expiresAfter(3600);
            return $this->eventSearch->searchByName($name);
        });
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function searchByDate(?\DateTimeImmutable $start = null, ?\DateTimeImmutable $end = null): array
    {
        return $this->cache->get(md5($start?->getTimestamp(). '_' . $end?->getTimestamp()), function (ItemInterface $item) use ($start, $end) {
           $item->expiresAfter(3600);
           return $this->eventSearch->searchByDate($start, $end);
        });
    }

    /**
     * @inheritDoc
     */
    public function searchByFilter(array $filters): array
    {
        return $this->cache->get(md5(json_encode($filters)), function (ItemInterface $item) use ($filters) {
           $item->expiresAfter(3600);
           return $this->eventSearch->searchByFilter($filters);
        });
    }
}