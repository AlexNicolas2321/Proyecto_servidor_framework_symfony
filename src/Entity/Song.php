<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    #[ORM\Column]
    private ?int $Duration = null;

    #[ORM\Column(length: 255)]
    private ?string $album = null;

    #[ORM\Column(length: 255)]
    private ?string $Author = null;

    #[ORM\Column]
    private ?int $Replays = null;

    #[ORM\Column]
    private ?int $Likes = null;

    #[ORM\Column(length: 255)]
    private ?string $File = null;


    #[ORM\ManyToOne(targetEntity: Style::class, inversedBy: 'songs')]
    #[ORM\JoinColumn(nullable: true, onDelete:"SET NULL")]
    private ?Style $Genre = null;

    /**
     * @var Collection<int, PlaylistSong>
     */
    #[ORM\OneToMany(mappedBy: 'song', targetEntity: PlaylistSong::class, cascade:["persist"])]
    private Collection $playlistSongs;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'songs')]
    private Collection $users;

   

    public function __construct()
    {
        $this->playlistSongs = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

   

    public function setAlbum(string $album): self
    {
        $this->album = $album;
        return $this;
    }
    
 


public function setDuration(int $duration): self
{
    $this->Duration = $duration;
    return $this;
}
  

    public function getId(): ?int
    {
        return $this->id;
        
    }
    public function setTitle(string $Title): self
    {
        $this->Title = $Title;
        return $this;
    }
    
    public function getTitle(): ?string
    {
        return $this->Title;
    }
    
   
    public function getDuration(): ?int
    {
        return $this->Duration;
    }

   

    public function getAlbum(): ?string
    {
        return $this->album;
    }

   
    public function getAuthor(): ?string
    {
        return $this->Author;
    }


    public function getReplays(): ?int
    {
        return $this->Replays;
    }



    public function getLikes(): ?int
    {
        return $this->Likes;
    }



    public function getGenre(): ?Style
    {
        return $this->Genre;
    }
    public function setGenre(?Style $genre): self
    {
        $this->Genre = $genre;
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
            $playlistSong->setSong($this);
        }
        return $this;
    }

    public function removePlaylistSong(PlaylistSong $playlistSong): self
    {
        if ($this->playlistSongs->removeElement($playlistSong)) {
            if ($playlistSong->getSong() === $this) {
                $playlistSong->setSong(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addSong($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeSong($this);
        }

        return $this;
    }

 
public function setAuthor(string $author): self
{
    $this->Author = $author;
    return $this;
}

public function setReplays(int $replays): self
{
    $this->Replays = $replays;
    return $this;
}

public function setLikes(int $likes): self
{
    $this->Likes = $likes;
    return $this;
}
  
    

   

    

    public function __toString(): string
    {
        return $this->Title . ' by ' . $this->Author;
    }

    public function getFile(): ?string
    {
        return $this->File;
    }

    public function setFile(string $File): static
    {
        $this->File = $File;

        return $this;
    }
}
