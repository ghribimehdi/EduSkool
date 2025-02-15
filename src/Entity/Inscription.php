<?php
namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateInscription;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'inscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $etudiant = null;

    #[ORM\ManyToOne(targetEntity: Activity::class, inversedBy: 'inscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activity $activity = null;

    #[ORM\Column(type: 'string', length: 20)]
    private string $etat;

    public function __construct()
    {
        $this->dateInscription = new \DateTime();
        $this->etat = 'en attente';
    }

    public function getId(): ?int { return $this->id; }
    public function getDateInscription(): ?\DateTimeInterface { return $this->dateInscription; }
    public function getEtudiant(): ?User { return $this->etudiant; }
    public function setEtudiant(?User $etudiant): self { $this->etudiant = $etudiant; return $this; }
    public function getActivity(): ?Activity { return $this->activity; }
    public function setActivity(?Activity $activity): self { $this->activity = $activity; return $this; }
    public function getEtat(): ?string { return $this->etat; }
    public function setEtat(string $etat): self { $this->etat = $etat; return $this; }

    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }
}