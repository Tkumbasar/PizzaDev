<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?int $totalPrice = null;

    /**
     * @var Collection<int, Menu>
     */
    #[ORM\ManyToMany(targetEntity: Menu::class, inversedBy: 'orders')]
    private Collection $orderMenu;

    /**
     * @var Collection<int, Customer>
     */
    #[ORM\ManyToMany(targetEntity: Customer::class, inversedBy: 'orders')]
    private Collection $orderCustomer;

    public function __construct()
    {
        $this->orderMenu = new ArrayCollection();
        $this->orderCustomer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getOrderMenu(): Collection
    {
        return $this->orderMenu;
    }

    public function addOrderMenu(Menu $orderMenu): static
    {
        if (!$this->orderMenu->contains($orderMenu)) {
            $this->orderMenu->add($orderMenu);
        }

        return $this;
    }

    public function removeOrderMenu(Menu $orderMenu): static
    {
        $this->orderMenu->removeElement($orderMenu);

        return $this;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getOrderCustomer(): Collection
    {
        return $this->orderCustomer;
    }

    public function addOrderCustomer(Customer $orderCustomer): static
    {
        if (!$this->orderCustomer->contains($orderCustomer)) {
            $this->orderCustomer->add($orderCustomer);
        }

        return $this;
    }

    public function removeOrderCustomer(Customer $orderCustomer): static
    {
        $this->orderCustomer->removeElement($orderCustomer);

        return $this;
    }
}
