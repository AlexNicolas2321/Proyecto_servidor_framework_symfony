<?php

namespace App\Entity;

use App\Repository\PlaylistSongRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaylistSongRepository::class)]
class PlaylistSong
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Playlist::class, inversedBy: 'playlistSongs')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Playlist $playlist = null;

    #[ORM\ManyToOne(targetEntity: Song::class, inversedBy: 'playlistSongs')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Song $song = null;

    #[ORM\Column(type: 'integer')]
    private int $replays = 0;

    public function __toString(): string
    {
        return $this->getSong()->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(?Playlist $playlist): self
    {
        $this->playlist = $playlist;
        return $this;
    }

    public function getSong(): ?Song
    {
        return $this->song;
    }

    public function setSong(?Song $song): self
    {
        $this->song = $song;
        return $this;
    }

    public function getReplays(): int
    {
        return $this->replays;
    }

    public function setReplays(int $replays): self
    {
        $this->replays = $replays;
        return $this;
    }
}
