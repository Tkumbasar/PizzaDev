<?php

namespace App\Entity;

use App\Entity\User; 
use App\Repository\ChefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ChefRepository::class)]
class Chef
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_of_birthday = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\Column(length: 255)]
    private ?string $gender = null;

    /**
     * @var Collection<int, Menu>
     */
    #[ORM\OneToMany(targetEntity: Menu::class, mappedBy: 'chef')]
    private Collection $chef;

    #[ORM\OneToOne(inversedBy: 'chef', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userChef = null;

    #[Vich\UploadableField(mapping: 'chef_images', fileNameProperty: 'picture')]
    private ?File $imageFile = null;

    
    public function __construct()
    {
        $this->chef = new ArrayCollection();
    }
    
    public function __toString() {
        return $this->name . " " .  $this->firstname;
    }
    
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getDateOfBirthday(): ?\DateTimeInterface
    {
        return $this->date_of_birthday;
    }

    public function setDateOfBirthday(\DateTimeInterface $date_of_birthday): static
    {
        $this->date_of_birthday = $date_of_birthday;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getChef(): Collection
    {
        return $this->chef;
    }

    public function addChef(Menu $chef): static
    {
        if (!$this->chef->contains($chef)) {
            $this->chef->add($chef);
            $chef->setChef($this);
        }

        return $this;
    }

    public function removeChef(Menu $chef): static
    {
        if ($this->chef->removeElement($chef)) {
            // set the owning side to null (unless already changed)
            if ($chef->getChef() === $this) {
                $chef->setChef(null);
            }
        }

        return $this;
    }

    public function getUserChef(): ?User
    {
        return $this->userChef;
    }

    public function setUserChef(User $userChef): static
    {
        $this->userChef = $userChef;

        return $this;
    }
    
   


}
