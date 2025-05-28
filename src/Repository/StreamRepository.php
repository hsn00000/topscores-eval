<?php

namespace App\Repository;

use App\Entity\Stream;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stream>
 */
class StreamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stream::class);
    }

    //this function gets the streams planned tomorrow
    public function findStreamsTomorrow(): array
    {
        $tomorrow = new \DateTime('tomorrow');
        //clone the date to avoid modifying the original object
        //set the time to the beginning and end of the day
        $tomorrowStart = (clone $tomorrow)->setTime(0, 0, 0);
        //set the time to the end of the day        
        $tomorrowEnd = (clone $tomorrow)->setTime(23, 59, 59);
        $qb = $this->createQueryBuilder('s')
            ->where('s.dateStart >= :start')
            ->andWhere('s.dateStart <= :end')
            ->setParameter('start', $tomorrowStart)
            ->setParameter('end', $tomorrowEnd);
        //get SQL query
        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Stream[] Returns an array of Stream objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Stream
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
