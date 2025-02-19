<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnseignantRepository::class)]
class Enseignant
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
     * @var Collection<int, Devoir>
     */
    #[ORM\OneToMany(targetEntity: Devoir::class, mappedBy: 'enseignant')]
    private Collection $devoirs;
    /**
 * @var Collection<int, Participant>
 */
#[ORM\OneToMany(targetEntity: Participant::class, mappedBy: 'enseignant')]
private Collection $participants;

    public function __construct()
    {
        $this->devoirs = new ArrayCollection();
        $this->participants = new ArrayCollection();
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
            $devoir->setEnseignant($this);
        }

        return $this;
    }

    public function removeDevoir(Devoir $devoir): static
    {
        if ($this->devoirs->removeElement($devoir)) {
            // set the owning side to null (unless already changed)
            if ($devoir->getEnseignant() === $this) {
                $devoir->setEnseignant(null);
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
        $participant->setEnseignant($this);
    }
    return $this;
}

public function removeParticipant(Participant $participant): static
{
    if ($this->participants->removeElement($participant)) {
        if ($participant->getEnseignant() === $this) {
            $participant->setEnseignant(null);
        }
    }
    return $this;
}
}
