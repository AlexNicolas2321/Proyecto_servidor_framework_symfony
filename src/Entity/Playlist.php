<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(length: 255)]
    private ?bool $Visibility = null;

    #[ORM\Column]
    private ?int $Replays = null;

    #[ORM\Column]
    private ?int $Likes = null;

    #[ORM\ManyToOne(inversedBy: 'playlists')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $Owner = null;

    /**
     * @var Collection<int, PlaylistSong>
     */
    #[ORM\OneToMany(mappedBy: 'playlist', targetEntity: PlaylistSong::class, cascade: ['persist'])]
    private Collection $playlistSongs;


    /**
     * @var Collection<int, UserPlaylist>
     */
    #[ORM\OneToMany(targetEntity: UserPlaylist::class, mappedBy: 'playlists', cascade: ['persist'])]
    private Collection $userPlaylists;

    public function __construct()
    {
        $this->playlistSongs = new ArrayCollection();
        $this->userPlaylists = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name)
    {
        $this->Name = $Name;

        return $this;
    }

    public function getVisibility(): ?bool
    {
        return $this->Visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->Visibility = $visibility;
    
        return $this;
    }

    public function getReplays(): ?int
    {
        return $this->Replays;
    }

    public function setReplays(int $Replays)
    {
        $this->Replays = $Replays;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->Likes;
    }

    public function setLikes(int $Likes)
    {
        $this->Likes = $Likes;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->Owner;
    }

    public function setOwner(?User $Owner)
    {
        $this->Owner = $Owner;

        return $this;
    }

    /**
     * @return Collection<int, PlaylistSong>
     */
    public function getPlaylistSongs(): Collection
    {
        return $this->playlistSongs;
    }

    public function addPlaylistSong(PlaylistSong $playlistSong): self
    {
        if (!$this->playlistSongs->contains($playlistSong)) {
            $this->playlistSongs->add($playlistSong);
            $playlistSong->setPlaylist($this);
        }
        return $this;
    }

    public function removePlaylistSong(PlaylistSong $playlistSong): self
    {
        if ($this->playlistSongs->removeElement($playlistSong)) {
            if ($playlistSong->getPlaylist() === $this) {
                $playlistSong->setPlaylist(null);
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

    public function addUserPlaylist(UserPlaylist $userPlaylist): static
    {
        if (!$this->userPlaylists->contains($userPlaylist)) {
            $this->userPlaylists->add($userPlaylist);
            $userPlaylist->setPlaylists($this);
        }

        return $this;
    }

    public function removeUserPlaylist(UserPlaylist $userPlaylist): static
    {
        if ($this->userPlaylists->removeElement($userPlaylist)) {
            // set the owning side to null (unless already changed)
            if ($userPlaylist->getPlaylists() === $this) {
                $userPlaylist->setPlaylists(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->Name ?? 'Unnamed Playlist';
    }
}
