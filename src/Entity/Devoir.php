<?php

namespace App\Entity;

use App\Repository\DevoirRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevoirRepository::class)]
class Devoir
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datelimite = null;

    #[ORM\ManyToOne(inversedBy: 'devoirs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Enseignant $enseignant = null;

    /**
     * @var Collection<int, SoumissionDevoir>
     */
    #[ORM\OneToMany(targetEntity: SoumissionDevoir::class, mappedBy: 'devoir', orphanRemoval: true)]
    private Collection $soumissionDevoirs;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $fichier = null;

    public function __construct()
    {
        $this->soumissionDevoirs = new ArrayCollection();
        $this->participants = new ArrayCollection();
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDatelimite(): ?\DateTimeInterface
    {
        return $this->datelimite;
    }

    public function setDatelimite(\DateTimeInterface $datelimite): static
    {
        $this->datelimite = $datelimite;

        return $this;
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): static
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    /**
     * @return Collection<int, SoumissionDevoir>
     */
    public function getSoumissionDevoirs(): Collection
    {
        return $this->soumissionDevoirs;
    }
    /**
     * @var Collection<int, Participant>
     */
    #[ORM\ManyToMany(targetEntity: Participant::class, inversedBy: 'devoirs')]
    #[ORM\JoinTable(name: 'devoir_participant')]
    private Collection $participants;

    public function addSoumissionDevoir(SoumissionDevoir $soumissionDevoir): static
    {
        if (!$this->soumissionDevoirs->contains($soumissionDevoir)) {
            $this->soumissionDevoirs->add($soumissionDevoir);
            $soumissionDevoir->setDevoir($this);
        }

        return $this;
    }

    public function removeSoumissionDevoir(SoumissionDevoir $soumissionDevoir): static
    {
        if ($this->soumissionDevoirs->removeElement($soumissionDevoir)) {
            // set the owning side to null (unless already changed)
            if ($soumissionDevoir->getDevoir() === $this) {
                $soumissionDevoir->setDevoir(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }
        return $this;
    }

    public function removeParticipant(Participant $participant): static
    {
        $this->participants->removeElement($participant);
        return $this;
    }
}
    
    

