<?php

namespace App\Entity;
use App\Entity\Chef; 
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\OneToOne(mappedBy: 'userCustomer', cascade: ['persist', 'remove'])]
    private ?Customer $customer = null;

    #[ORM\OneToOne(mappedBy: 'userChef', cascade: ['persist', 'remove'])]
    private ?Chef $chef = null;

    private $isChefRequested = false;
    private $isChefAccepted = false;
    private $profileComplete = false;

    /**
     * @var Collection<int, ChefRequest>
     */
    #[ORM\OneToMany(targetEntity: ChefRequest::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $chefRequests;

    public function __construct()
    {
        $this->chefRequests = new ArrayCollection();
    }


    public function getIsChefRequested(): ?bool
    {
        return $this->isChefRequested;
    }

    public function setIsChefRequested(bool $isChefRequested): self
    {
        $this->isChefRequested = $isChefRequested;
        return $this;
    }

    public function getIsChefAccepted(): ?bool
    {
        return $this->isChefAccepted;
    }

    public function setIsChefAccepted(bool $isChefAccepted): self
    {
        $this->isChefAccepted = $isChefAccepted;
        return $this;
    }

    public function getProfileComplete(): ?bool
    {
        return $this->profileComplete;
    }

    public function setProfileComplete(bool $profileComplete): self
    {
        $this->profileComplete = $profileComplete;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString() {
        return $this->email;
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function initializeRoles(): void
    {
        if (empty($this->roles)) {
            $this->roles[] = 'ROLE_USER';
        }
    }
    
    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }
    public function addRole(string $role): static
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): static
    {
        if (false !== $key = array_search($role, $this->roles)) {
            unset($this->roles[$key]);
        }

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): static
    {
        // set the owning side of the relation if necessary
        if ($customer->getUserCustomer() !== $this) {
            $customer->setUserCustomer($this);
        }

        $this->customer = $customer;

        return $this;
    }

    public function getChef(): ?Chef
    {
        return $this->chef;
    }

    public function setChef(Chef $chef): static
    { 
        $this->chef = $chef;
        // set the owning side of the relation if necessary
        if ($chef->getUserChef() !== $this) {
            $chef->setUserChef($this);
        }
        

       

        return $this;
    }

    /**
     * @return Collection<int, ChefRequest>
     */
    public function getChefRequests(): Collection
    {
        return $this->chefRequests;
    }

    public function addChefRequest(ChefRequest $chefRequest): static
    {
        if (!$this->chefRequests->contains($chefRequest)) {
            $this->chefRequests->add($chefRequest);
            $chefRequest->setUser($this);
        }

        return $this;
    }

    public function removeChefRequest(ChefRequest $chefRequest): static
    {
        if ($this->chefRequests->removeElement($chefRequest)) {
            // set the owning side to null (unless already changed)
            if ($chefRequest->getUser() === $this) {
                $chefRequest->setUser(null);
            }
        }

        return $this;
    }
}
