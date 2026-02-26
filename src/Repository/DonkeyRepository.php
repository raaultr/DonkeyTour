<?php

namespace App\Repository;

use App\Entity\Donkey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Donkey>
 */
class DonkeyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Donkey::class);
    }

    /**
     * Encuentra burros disponibles para una fecha concreta.
     * Excluye los que ya tienen reserva ese día.
     *
     * @return Donkey[]
     */
    public function findAvailableForDate(\DateTime $date): array
    {
        $dayStart = (clone $date)->setTime(0, 0, 0);
        $dayEnd   = (clone $date)->setTime(23, 59, 59);

        return $this->createQueryBuilder('d')
            ->where('d.disponible = true')
            ->andWhere('d.deletedAt IS NULL')
            ->andWhere(
                'd.id NOT IN (
                    SELECT IDENTITY(r.selectedDonkey)
                    FROM App\Entity\Reserve r
                    WHERE r.reserveDate BETWEEN :dayStart AND :dayEnd
                    AND r.deletedAt IS NULL
                    AND r.selectedDonkey IS NOT NULL
                )'
            )
            ->setParameter('dayStart', $dayStart)
            ->setParameter('dayEnd', $dayEnd)
            ->orderBy('d.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Encuentra todos los burros disponibles (sin filtro de fecha).
     * Usado para apadrinamiento.
     *
     * @return Donkey[]
     */
    public function findAllAvailable(): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.disponible = true')
            ->andWhere('d.deletedAt IS NULL')
            ->orderBy('d.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
