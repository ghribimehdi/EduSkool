<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string', length: 100)]
    private string $lastname;

    #[ORM\Column(type: 'string', length: 100)]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private string $address;

    #[ORM\Column(type: 'string', length: 5)]
    private string $zipcode;

    #[ORM\Column(type: 'string', length: 150)]
    private string $city;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageFileName = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $created_at;

    // Relation avec Activity supprimée :
    // #[ORM\OneToMany(mappedBy: 'enseignant', targetEntity: Activity::class)]
    // private Collection $activities;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Inscription::class)]
    private Collection $inscriptions;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        // $this->activities = new ArrayCollection(); // Ligne supprimée
        $this->inscriptions = new ArrayCollection();
    }

    public function getId(): ?int 
    { 
        return $this->id; 
    }

    public function getEmail(): ?string 
    { 
        return $this->email; 
    }

    public function setEmail(string $email): self 
    { 
        $this->email = $email; 
        return $this; 
    }

    public function getUserIdentifier(): string 
    { 
        return (string) $this->email; 
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self 
    { 
        $this->roles = $roles; 
        return $this; 
    }

    public function getPassword(): string 
    { 
        return $this->password; 
    }

    public function setPassword(string $password): self 
    { 
        $this->password = $password; 
        return $this; 
    }

    public function eraseCredentials(): void 
    {}

    public function getLastname(): ?string 
    { 
        return $this->lastname; 
    }

    public function setLastname(string $lastname): self 
    { 
        $this->lastname = $lastname; 
        return $this; 
    }

    public function getFirstname(): ?string 
    { 
        return $this->firstname; 
    }

    public function setFirstname(string $firstname): self 
    { 
        $this->firstname = $firstname; 
        return $this; 
    }

    public function getAddress(): ?string 
    { 
        return $this->address; 
    }

    public function setAddress(string $address): self 
    { 
        $this->address = $address; 
        return $this; 
    }

    public function getZipcode(): ?string 
    { 
        return $this->zipcode; 
    }

    public function setZipcode(string $zipcode): self 
    { 
        $this->zipcode = $zipcode; 
        return $this; 
    }

    public function getCity(): ?string 
    { 
        return $this->city; 
    }

    public function setCity(string $city): self 
    { 
        $this->city = $city; 
        return $this; 
    }

    public function getCreatedAt(): ?\DateTimeImmutable 
    { 
        return $this->created_at; 
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->created_at = $createdAt;
        return $this;
    }

    public function getImageFileName(): ?string
    {
        return $this->imageFileName;
    }

    public function setImageFileName(?string $imageFileName): static
    {
        $this->imageFileName = $imageFileName;

        return $this;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setEtudiant($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getEtudiant() === $this) {
                $inscription->setEtudiant(null);
            }
        }

        return $this;
    }
}
