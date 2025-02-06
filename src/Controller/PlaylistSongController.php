<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\Song;
use App\Entity\PlaylistSong;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class PlaylistSongController extends AbstractController
{
    #[Route('/playlist-song/new', name: 'app_playlist_song_new')]
    public function createPlaylistWithSong(EntityManagerInterface $entityManager): JsonResponse
    {
        // Crear usuario para la playlist
        $user = new User();
        $user->setName('John Doe')
             ->setEmail('john@example.com')
             ->setPassword('password123')
             ->setBirthDate(new \DateTime('1990-01-01'));

        // Crear playlist
        $playlist = new Playlist();
        $playlist->setName('Mi Playlist Rock')
            ->setVisibility('public')
            ->setReplays(0)
            ->setLikes(0)
            ->setOwner($user);

        // Crear canción
        $song = new Song();
        $song->setTitle('Sweet Child O Mine')
            ->setDuration(356)
            ->setAlbum('Appetite for Destruction')
            ->setAuthor('Guns N Roses')
            ->setReplays(0)
            ->setLikes(0);

        // Crear la relación PlaylistSong
        $playlistSong = new PlaylistSong();
        $playlistSong->setPlaylist($playlist)
                    ->setSong($song)
                    ->setReplays(0);

        // Persistir todo
        $entityManager->persist($user);
        $entityManager->persist($playlist);
        $entityManager->persist($song);
        $entityManager->persist($playlistSong);
        $entityManager->flush();

        return $this->json([
            'message' => 'Playlist con canción creada exitosamente',
            'data' => [
                'playlist' => [
                    'name' => $playlist->getName(),
                    'owner' => $user->getName()
                ],
                'song' => [
                    'title' => $song->getTitle(),
                    'author' => $song->getAuthor(),
                    'album' => $song->getAlbum()
                ],
                'reproductions' => $playlistSong->getReplays()
            ]
        ]);
    }
}
