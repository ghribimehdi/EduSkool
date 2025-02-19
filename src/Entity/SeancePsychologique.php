<?php

namespace App\Entity;

use App\Repository\SeancePsychologiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SeancePsychologiqueRepository::class)]
class SeancePsychologique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "La date de la séance est obligatoire.")]
    private ?\DateTimeInterface $dateSeance = null;

    #[ORM\Column(type: 'string', length: 20)]
    private $status = 'pending';

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type de séance est obligatoire.")]
    private ?string $typeSeance = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: "La durée de la séance est obligatoire.")]
    private ?int $duree = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du participant est obligatoire.")]
    private ?string $nom_participant = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du psychologue est obligatoire.")]
    private ?string $nom_psychologue = null;

    #[ORM\OneToMany(targetEntity: SuiviPsychologique::class, mappedBy: 'seance', cascade: ['persist', 'remove'])]
    private Collection $suivis;

    public function __construct()
    {
        $this->suivis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSeance(): ?\DateTimeInterface
    {
        return $this->dateSeance;
    }

    public function setDateSeance(\DateTimeInterface $dateSeance): self
    {
        $this->dateSeance = $dateSeance;
        return $this;
    }

    public function getTypeSeance(): ?string
    {
        return $this->typeSeance;
    }

    public function setTypeSeance(string $typeSeance): self
    {
        $this->typeSeance = $typeSeance;
        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;
        return $this;
    }

    public function getNomParticipant(): ?string
    {
        return $this->nom_participant;
    }

    public function setNomParticipant(string $nom_participant): self
    {
        $this->nom_participant = $nom_participant;
        return $this;
    }

    public function getNomPsychologue(): ?string
    {
        return $this->nom_psychologue;
    }

    public function setNomPsychologue(string $nom_psychologue): self
    {
        $this->nom_psychologue = $nom_psychologue;
        return $this;
    }

    public function getSuivis(): Collection
    {
        return $this->suivis;
    }

    public function addSuivi(SuiviPsychologique $suivi): self
    {
        if (!$this->suivis->contains($suivi)) {
            $this->suivis[] = $suivi;
            $suivi->setSeance($this);
        }

        return $this;
    }

    public function removeSuivi(SuiviPsychologique $suivi): self
    {
        if ($this->suivis->removeElement($suivi)) {
            // set the owning side to null (unless already changed)
            if ($suivi->getSeance() === $this) {
                $suivi->setSeance(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

}