<?php

namespace App\Repository;

use App\Entity\Event;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
        $qb = $this->createQueryBuilder('e');
        if( !($startDate || $endDate ) ) {
            throw new InvalidArgumentException('At least one date must be provided.');
        }

        if( $startDate ) {
            $qb->andWhere('e.startAt >= :startDate')
                ->setParameter('startDate', $startDate);
        }
        if( $endDate ) {
            $qb->andWhere('e.endAt <= :endDate')
                ->setParameter('endDate', $endDate);
        }
        return $qb->getQuery()
                  ->getResult();
    }
}
