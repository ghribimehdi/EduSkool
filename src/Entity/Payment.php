<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Payment
{#[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

   

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $amount =30.00;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $paymentDate = null;

    #[ORM\Column(length: 50)]
    private ?string $paymentMethod = null; // Ex: "Credit Card", "PayPal"

    #[ORM\Column(length: 20)]
    private ?string $status = 'SUCCESS'; // SUCCESS, FAILED, PENDING

    #[ORM\ManyToOne(targetEntity: Pack::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pack $pack = null; // Relationship with Pack

    // Real Payment Card Information (should be securely handled)
    #[ORM\Column(length: 16, nullable: true)]
    private ?string $cardNumber = null; // Credit card number (should not be stored directly)

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $cardExpiration = null; // Expiry date (MM/YY)

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $cardCVV = null; // Card CVV (should not be stored directly)

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): self
    {
        $this->pack = $pack;
        return $this;
    }



    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(\DateTimeInterface $paymentDate): self
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

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

    // Getters and Setters for Payment Card Information

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(?string $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getCardExpiration(): ?string
    {
        return $this->cardExpiration;
    }

    public function setCardExpiration(?string $cardExpiration): self
    {
        $this->cardExpiration = $cardExpiration;

        return $this;
    }

    public function getCardCVV(): ?string
    {
        return $this->cardCVV;
    }

    public function setCardCVV(?string $cardCVV): self
    {
        $this->cardCVV = $cardCVV;

        return $this;
    }
}