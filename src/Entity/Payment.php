<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentMethod = null;

    #[ORM\OneToOne(mappedBy: 'orderPayment', cascade: ['persist', 'remove'])]
    private ?Order $orderPayment = null;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getOrderPayment(): ?Order
    {
        return $this->orderPayment;
    }

    public function setOrderPayment(?Order $orderPayment): static
    {
        // unset the owning side of the relation if necessary
        if ($orderPayment === null && $this->orderPayment !== null) {
            $this->orderPayment->setOrderPayment(null);
        }

        // set the owning side of the relation if necessary
        if ($orderPayment !== null && $orderPayment->getOrderPayment() !== $this) {
            $orderPayment->setOrderPayment($this);
        }

        $this->orderPayment = $orderPayment;

        return $this;
    }

}
