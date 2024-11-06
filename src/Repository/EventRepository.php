<?php

namespace App\Repository;

use App\Entity\Event;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return array<Event>
     */
    public function findAllByDates(?DateTimeImmutable $startDate = null, ?DateTimeImmutable $endDate = null): array
    {
        if (!($startDate || $endDate)) {
            throw new InvalidArgumentException('At least one date must be provided.');
        }
        $qb = $this->createQueryBuilder('e');
        $this->addDateFilters($startDate, $endDate, $qb);
        return $qb->getQuery()
                  ->getResult();
    }

    public function findLikeName(?string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('At least one character must be provided.');
        }
        $qb = $this->createQueryBuilder('e');
        $this->addNameFilter($name, $qb);
        return $qb->getQuery()
                  ->getResult();
    }

    public function addDateFilters(?DateTimeImmutable $startDate, ?DateTimeImmutable $endDate, QueryBuilder $qb): static
    {
        if ($startDate) {
            $qb->andWhere('e.startDate >= :startDate')
                ->setParameter('startDate', $startDate);
        }
        if ($endDate) {
            $qb->andWhere('e.endDate <= :endDate')
                ->setParameter('endDate', $endDate);
        }
        return $this;
    }

    public function addNameFilter(?string $name, QueryBuilder $qb): static
    {
        if (!empty($name)) {
            $qb->andWhere('e.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }
        return $this;
    }

    public function findByFilter(array $filters): array
    {
        $qb = $this->createQueryBuilder('e');
        $this->addDateFilters($filters['startDate'] ?? null, $filters['endDate'] ?? null, $qb)
             ->addNameFilter($filters['name'] ?? null, $qb);
        return $qb->getQuery()
                  ->getResult();
    }
}
