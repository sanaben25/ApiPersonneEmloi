<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Controller\PersonneController;
use Doctrine\ORM\Mapping as ORM;

use  ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiProperty;
use App\Repository\PersonneEmploiRepository;

#[ORM\Entity(repositoryClass: PersonneEmploiRepository::class)]
#[ApiResource(operations: [
    new Post(),
    new Get(),
    new Get(
        name: 'get_personnes_by_entreprise',
            uriTemplate: '/personne_emplois',
            controller: PersonneController::class,
            openapiContext: [
                'summary' => 'Renvoie tous les emplois ayant travaillé pour une entreprise donnée',
                'parameters' => [
                    [
                        'name'        => 'nomEntreprise',
                        'in'          => 'query',
                        'description' => 'Collection page number',
                        'required'    => false,
                        'type'        => 'string',
                        'default'     => 1,
                    ]
                ],
            ],
    ),
    new Get(
        name: 'get_emplois_by_personne_dates',
            uriTemplate: '/personne_emplois/by_personne/{id}/dates',
            controller: PersonneController::class,
            openapiContext: [
                'summary' => 'Renvoie tous les emplois d\'une personne entre deux plages de dates',
                'parameters' => [
                    [
                        'name' => 'dateDebut',
                        'in' => 'query',
                        'required' => true,
                        'schema' => [
                            'type' => 'string',
                            'format' => 'date',
                        ],
                        'description' => 'Date de début de la plage',
                    ],
                    [
                        'name' => 'dateFin',
                        'in' => 'query',
                        'required' => true,
                        'schema' => [
                            'type' => 'string',
                            'format' => 'date',
                        ],
                        'description' => 'Date de fin de la plage',
                    ],
                ],
            ],
        ),
    ]
)]
class PersonneEmploi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Personne::class, inversedBy: 'emplois')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Personne $personne = null;

    #[ORM\ManyToOne(targetEntity: Emploi::class)]
    private ?Emploi $emploi = null;

    #[ORM\Column(type: "datetime")]
    #[Groups('personne')]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255)]
    private ?string $posteOccupe = null;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Personne|null
     */
    public function getPersonne()
    {
        return $this->personne;
    }

    /**
     * @param Personne|null $personne
     */
    public function setPersonne($personne)
    {
        $this->personne = $personne;
    }

    /**
     * @return Emploi|null
     */
    public function getEmploi()
    {
        return $this->emploi;
    }

    /**
     * @param Emploi|null $emploi
     */
    public function setEmploi($emploi)
    {
        $this->emploi = $emploi;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTimeInterface|null $dateDebut
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param \DateTimeInterface|null $dateFin
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return string|null
     */
    public function getPosteOccupe()
    {
        return $this->posteOccupe;
    }

    /**
     * @param string|null $posteOccupe
     */
    public function setPosteOccupe($posteOccupe)
    {
        $this->posteOccupe = $posteOccupe;
    }
}
