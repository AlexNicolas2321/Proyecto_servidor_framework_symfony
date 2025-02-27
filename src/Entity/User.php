<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: "email", length: 255)]  // Asegúrate de que el nombre sea 'email' en minúsculas
    private ?string $email = null;


    #[ORM\Column(length: 255)]
    private ?string $Password = null;

    #[ORM\Column(length: 255, nullable: true)] // <-- Agregar 'nullable: true'
    private ?string $Name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Birth_date = null;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Profile $Profile = null;

    /**
     * @var Collection<int, Playlist>
     */
    #[ORM\OneToMany(targetEntity: Playlist::class, mappedBy: 'Owner')]
    private Collection $playlists;

    /**
     * @var Collection<int, UserPlaylist>
     */
    #[ORM\OneToMany(targetEntity: UserPlaylist::class, mappedBy: 'users')]
    private Collection $userPlaylists;

    /**
     * @var Collection<int, Song>
     */
    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'users')]
    private Collection $songs;

// Hacer nullable la columna 'roles'
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $roles = null; // Inicializado como null
    public function __construct()
    {
        $this->userPlaylists = new ArrayCollection();
        $this->songs = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->Name ?? 'Unnamed User'; // Devuelve el nombre del usuario o un valor por defecto
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

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->Birth_date;
    }

    public function setBirthDate(\DateTimeInterface $Birth_date): self
    {
        $this->Birth_date = $Birth_date;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->Profile;
    }

    public function setProfile(Profile $Profile): self
    {
        $this->Profile = $Profile;

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->setOwner($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->playlists->removeElement($playlist)) {
            // set the owning side to null (unless already changed)
            if ($playlist->getOwner() === $this) {
                $playlist->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserPlaylist>
     */
    public function getUserPlaylists(): Collection
    {
        return $this->userPlaylists;
    }

    public function addUserPlaylist(UserPlaylist $userPlaylist): self
    {
        if (!$this->userPlaylists->contains($userPlaylist)) {
            $this->userPlaylists->add($userPlaylist);
            $userPlaylist->setUsers($this);
        }

        return $this;
    }

    public function removeUserPlaylist(UserPlaylist $userPlaylist): self
    {
        if ($this->userPlaylists->removeElement($userPlaylist)) {
            // set the owning side to null (unless already changed)
            if ($userPlaylist->getUsers() === $this) {
                $userPlaylist->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Song>
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): self
    {
        if (!$this->songs->contains($song)) {
            $this->songs->add($song);
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        $this->songs->removeElement($song);

        return $this;
    }

    // Métodos requeridos por UserInterface
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        // Si no se han asignado roles, se asigna el rol por defecto "ROLE_USER"
        return $this->roles ?: ['ROLE_USER'];
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // No se almacena información sensible en la entidad, así que no es necesario hacer nada aquí
    }
}
