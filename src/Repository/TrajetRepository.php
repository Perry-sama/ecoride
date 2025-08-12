<?php

namespace App\Repository;

use App\Entity\Trajet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trajet>
 */
class TrajetRepository extends ServiceEntityRepository
{
public function __construct(ManagerRegistry $registry)
{
    parent::__construct($registry, Trajet::class);
}

public function findBySearch(?string $depart, ?string $destination): array
{
    $qb = $this->createQueryBuilder('t');

    if ($depart) {
        $qb->andWhere('t.depart LIKE :depart')
           ->setParameter('depart', '%' . $depart . '%');
    }

    if ($destination) {
        $qb->andWhere('t.destination LIKE :destination')
           ->setParameter('destination', '%' . $destination . '%');
    }

    return $qb->orderBy('t.dateHeure', 'ASC')
              ->getQuery()
              ->getResult();
}

    //    /**
    //     * @return Trajet[] Returns an array of Trajet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Trajet
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
