<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prénom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @var Collection<int, SoumissionDevoir>
     */
    #[ORM\OneToMany(targetEntity: SoumissionDevoir::class, mappedBy: 'participant')]
    private Collection $soumissions;

    /**
     * @var Collection<int, Devoir>
     */
    #[ORM\ManyToMany(targetEntity: Devoir::class, mappedBy: 'participants')]
    
    private Collection $devoirs;
    
    #[ORM\ManyToOne(targetEntity: Enseignant::class, inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Enseignant $enseignant = null;

    public function __construct()
    {
        $this->soumissions = new ArrayCollection();
        $this->devoirs = new ArrayCollection();
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

    public function getPrénom(): ?string
    {
        return $this->prénom;
    }

    public function setPrénom(string $prénom): static
    {
        $this->prénom = $prénom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, SoumissionDevoir>
     */
    public function getSoumissions(): Collection
    {
        return $this->soumissions;
    }

    public function addSoumission(SoumissionDevoir $soumission): static
    {
        if (!$this->soumissions->contains($soumission)) {
            $this->soumissions->add($soumission);
            $soumission->setParticipant($this);
        }

        return $this;
    }

    public function removeSoumission(SoumissionDevoir $soumission): static
    {
        if ($this->soumissions->removeElement($soumission)) {
            // set the owning side to null (unless already changed)
            if ($soumission->getParticipant() === $this) {
                $soumission->setParticipant(null);
            }
        }

        return $this;
    }
     /**
     * @return Collection<int, Devoir>
     */
    public function getDevoirs(): Collection
    {
        return $this->devoirs;
    }

    public function addDevoir(Devoir $devoir): static
    {
        if (!$this->devoirs->contains($devoir)) {
            $this->devoirs->add($devoir);
        }
        return $this;
    }

    public function removeDevoir(Devoir $devoir): static
    {
        $this->devoirs->removeElement($devoir);
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

}

