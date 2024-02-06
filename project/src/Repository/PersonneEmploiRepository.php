<?php

namespace App\Repository;

use App\Entity\Emploi;
use App\Entity\Personne;
use App\Entity\PersonneEmploi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emploi>
 *
 * @method Emploi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emploi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emploi[]    findAll()
 * @method Emploi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneEmploiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonneEmploi::class);
    }


    public function findByNomEntreprise(string $nomEntreprise)
    {
        return $this->createQueryBuilder('pe')
            ->join('pe.emploi', 'e')
            ->where('e.nomEntreprise = :nomEntreprise')
            ->setParameter('nomEntreprise', $nomEntreprise)
            ->getQuery()
            ->getResult();
    }

    public function findEmploisByPersonneAndDateRange(int $personneId, \DateTime $dateDebut, \DateTime $dateFin)
    {
        return $this->createQueryBuilder('pe')
            ->where('pe.personne = :personneId')
            ->andWhere('pe.dateDebut >= :dateDebut AND (pe.dateFin IS NULL OR pe.dateFin <= :dateFin)')
            ->setParameter('personneId', $personneId)
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin)
            ->getQuery()
            ->getResult();
    }

}
