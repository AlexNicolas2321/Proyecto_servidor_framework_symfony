<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Photo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Description = null;

    
    #[ORM\OneToOne(mappedBy: 'Profile', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    /**
     * @var Collection<int, Style>
     */
    #[ORM\ManyToMany(targetEntity: Style::class, inversedBy: 'profiles')]
    private Collection $PreferedStyle;

    
   

    public function __construct()
    {
        $this->PreferedStyle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(string $Photo)
    {
        $this->Photo = $Photo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description)
    {
        $this->Description = $Description;

        return $this;
    }

   
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        // set the owning side of the relation if necessary
        if ($user->getProfile() !== $this) {
            $user->setProfile($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Style>
     */
    public function getPreferedStyle(): Collection
    {
        return $this->PreferedStyle;
    }

    public function addPreferedStyle(Style $preferedStyle): static
    {
        if (!$this->PreferedStyle->contains($preferedStyle)) {
            $this->PreferedStyle->add($preferedStyle);
        }

        return $this;
    }

    public function removePreferedStyle(Style $preferedStyle): static
    {
        $this->PreferedStyle->removeElement($preferedStyle);

        return $this;
    }

   
   
}
