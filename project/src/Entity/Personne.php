<?php

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;

use App\Controller\PersonneController;
use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

use  ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[ApiResource(

operations: [
new Get(
    name: 'personnes_filter_nom',
            uriTemplate: '/personnes',
            controller: PersonneController::class,
            normalizationContext: ['groups' => ['personne:read']]

    ),
    new Post(
        name: 'add_personne',
        uriTemplate: '/personne',
        controller: PersonneController::class
    ),
    new Get()
    ]
)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('personne')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups('personne')]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\OneToMany(mappedBy: 'personne', targetEntity: PersonneEmploi::class)]
    private Collection $emplois;

    public function __construct()
    {
        $this->emplois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getEmplois(): Collection
    {
        return $this->emplois;
    }

    // Ajouter un emploi
    public function addEmploi(Emploi $emploi): self {
        if (!$this->emplois->contains($emploi)) {
            $this->emplois[] = $emploi;
            $emploi->setPersonne($this);
        }

        return $this;
    }

    // Supprimer un emploi
    public function removeEmploi(Emploi $emploi): self {
        if ($this->emplois->removeElement($emploi)) {
            // set the owning side to null (unless already changed)
            if ($emploi->getPersonne() === $this) {
                $emploi->setPersonne(null);
            }
        }

        return $this;
    }
}
