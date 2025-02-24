<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function findApprovedActivities(): array
{
    return $this->createQueryBuilder('a')
        ->where('a.isApproved = :approved')
        ->setParameter('approved', true)
        ->getQuery()
        ->getResult();
}



// src/Repository/ActivityRepository.php

public function getActivityCountByTypeAndDateLast10Days(): array
{
    $tenDaysAgo = new \DateTime();
    $tenDaysAgo->modify('-10 days');

    return $this->createQueryBuilder('a')
        ->select("SUBSTRING(a.createdAt, 1, 10) as date, a.typesActivity as type, COUNT(a.id) as count")
        ->where('a.createdAt >= :tenDaysAgo')
        ->setParameter('tenDaysAgo', $tenDaysAgo)
        ->groupBy("date, type")
        ->orderBy("date", "ASC")
        ->getQuery()
        ->getResult();
}




//  SELECT DATE(created_at) AS jour, types_activity, COUNT(*) AS total
//FROM activity
//GROUP BY DATE(created_at), types_activity
//ORDER BY DATE(created_at) ASC;
//  /**
//     * @return Activity[] Returns an array of Activity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Activity
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
