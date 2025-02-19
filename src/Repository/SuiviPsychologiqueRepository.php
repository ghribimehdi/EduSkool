<?php

namespace App\Repository;

use App\Entity\SuiviPsychologique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class SuiviPsychologiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuiviPsychologique::class);
    }

    public function findByParticipant(string $participantName): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.nom_participant = :participant')
            ->setParameter('participant', $participantName)
            ->orderBy('s.date_suivi', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByPsychologue(string $psychologueName): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.nom_psychologue = :psychologue')
            ->setParameter('psychologue', $psychologueName)
            ->orderBy('s.date_suivi', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.suivi_type = :type')
            ->setParameter('type', $type)
            ->orderBy('s.date_suivi', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findBySeance(int $seanceId): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.seance = :seanceId')
            ->setParameter('seanceId', $seanceId)
            ->orderBy('s.date_suivi', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByDateRange(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.date_suivi BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('s.date_suivi', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findRecentSuivis(int $limit = 10): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.date_suivi', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByEtatEmotionnel(string $etatEmotionnel): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.etat_emotionnel = :etatEmotionnel')
            ->setParameter('etatEmotionnel', $etatEmotionnel)
            ->orderBy('s.date_suivi', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getStatsByPsychologue(): array
    {
        return $this->createQueryBuilder('s')
            ->select('s.nom_psychologue, COUNT(s.id) as total_suivis')
            ->groupBy('s.nom_psychologue')
            ->getQuery()
            ->getResult();
    }

    private function getDefaultQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.date_suivi', 'DESC');
    }
}