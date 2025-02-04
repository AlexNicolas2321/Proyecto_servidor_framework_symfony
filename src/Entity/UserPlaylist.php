<?php

namespace App\Entity;

use App\Repository\UserPlaylistRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserPlaylistRepository::class)]
class UserPlaylist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userPlaylists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $users = null;

    #[ORM\ManyToOne(inversedBy: 'userPlaylists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Playlist $playlists = null;

    #[ORM\Column]
    private ?int $reproductions = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;
        return $this;
    }

    public function getPlaylists(): ?Playlist
    {
        return $this->playlists;
    }

    public function setPlaylists(?Playlist $playlists): self
    {
        $this->playlists = $playlists;
        return $this;
    }

    public function getReproductions(): ?int
    {
        return $this->reproductions;
    }

    public function setReproductions(int $reproductions): self
    {
        $this->reproductions = $reproductions;
        return $this;
    }
}
