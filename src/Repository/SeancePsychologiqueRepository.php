<?php

namespace App\Repository;

use App\Entity\SeancePsychologique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SeancePsychologiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeancePsychologique::class);
    }

    // Méthode pour trouver les séances par nom de participant
    public function findByParticipant(string $participantName): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.nom_participant LIKE :participant')
            ->setParameter('participant', '%'.$participantName.'%')
            ->orderBy('s.dateSeance', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Méthode pour trouver les séances par le nom du psychologue
    public function findByNomPsychologue(string $nomPsychologue): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.nom_psychologue = :nomPsychologue')
            ->setParameter('nomPsychologue', $nomPsychologue)
            ->orderBy('s.dateSeance', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Méthode pour trouver les séances par type
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.typeSeance = :type')
            ->setParameter('type', $type)
            ->orderBy('s.dateSeance', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Méthode pour trouver tous les statuts distincts des séances
    public function findAllStatuses()
    {
        return $this->createQueryBuilder('s')
            ->select('DISTINCT s.status')
            ->getQuery()
            ->getResult();
    }
}