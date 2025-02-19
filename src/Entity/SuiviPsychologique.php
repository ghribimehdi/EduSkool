<?php

namespace App\Entity;

use App\Repository\SuiviPsychologiqueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SuiviPsychologiqueRepository::class)]
class SuiviPsychologique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "La date du suivi est obligatoire.")]
    private ?\DateTimeInterface $date_suivi = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type de suivi est obligatoire.")]
    private ?string $suivi_type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'état émotionnel est obligatoire.")]
    private ?string $etat_emotionnel = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du participant est obligatoire.")]
    private ?string $nom_participant = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du psychologue est obligatoire.")]
    private ?string $nom_psychologue = null;

    #[ORM\ManyToOne(targetEntity: SeancePsychologique::class, inversedBy: 'suivis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SeancePsychologique $seance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSuivi(): ?\DateTimeInterface
    {
        return $this->date_suivi;
    }

    public function setDateSuivi(\DateTimeInterface $date_suivi): static
    {
        $this->date_suivi = $date_suivi;
        return $this;
    }

    public function getSuiviType(): ?string
    {
        return $this->suivi_type;
    }

    public function setSuiviType(string $suivi_type): static
    {
        $this->suivi_type = $suivi_type;
        return $this;
    }

    public function getEtatEmotionnel(): ?string
    {
        return $this->etat_emotionnel;
    }

    public function setEtatEmotionnel(string $etat_emotionnel): static
    {
        $this->etat_emotionnel = $etat_emotionnel;
        return $this;
    }

    public function getNomParticipant(): ?string
    {
        return $this->nom_participant;
    }

    public function setNomParticipant(string $nom_participant): static
    {
        $this->nom_participant = $nom_participant;
        return $this;
    }

    public function getNomPsychologue(): ?string
    {
        return $this->nom_psychologue;
    }

    public function setNomPsychologue(string $nom_psychologue): static
    {
        $this->nom_psychologue = $nom_psychologue;
        return $this;
    }

    public function getSeance(): ?SeancePsychologique
    {
        return $this->seance;
    }

    public function setSeance(?SeancePsychologique $seance): static
    {
        $this->seance = $seance;
        return $this;
    }

    public function __toString(): string
    {
        return $this->getNomParticipant() . ' - ' . $this->getDateSuivi()->format('Y-m-d H:i:s');
    }
}