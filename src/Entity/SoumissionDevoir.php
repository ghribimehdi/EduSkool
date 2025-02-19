<?php

namespace App\Entity;

use App\Repository\SoumissionDevoirRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SoumissionDevoirRepository::class)]
class SoumissionDevoir
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateSoumission = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $fichier = null;

    #[ORM\Column(nullable: true)]
    private ?int $note = null;

    #[ORM\ManyToOne(targetEntity: Participant::class, inversedBy: 'soumissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participant $participant = null;

    #[ORM\ManyToOne(targetEntity: Devoir::class, inversedBy: 'soumissionDevoirs')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Devoir $devoir = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __construct()
    {
        $this->dateSoumission = new \DateTime(); // Définit automatiquement la date actuelle
    }

    public function getDateSoumission(): ?\DateTimeInterface
    {
        return $this->dateSoumission;
    }

    public function setDateSoumission(\DateTimeInterface $dateSoumission): self
    {
        $this->dateSoumission = $dateSoumission;
        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(?string $fichier): self
    {
        $this->fichier = $fichier;
        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): self
    {
        $this->participant = $participant;
        return $this;
    }

    public function getDevoir(): ?Devoir
    {
        return $this->devoir;
    }

    public function setDevoir(?Devoir $devoir): self
    {
        $this->devoir = $devoir;
        return $this;
    }
}
