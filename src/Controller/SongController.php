<?php

namespace App\Controller;

use App\Entity\Song;
use App\Entity\Style;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Repository\SongRepository;
class SongController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }




    #[Route('/song/new', name: 'app_song')]
    public function index1(): JsonResponse
    {
        // Crear un estilo
        $style = new Style();
        $style->setName('Rock')
            ->setDescription('Género musical caracterizado por guitarras eléctricas y batería');

        // Crear una canción
        $song = new Song();
        $song->setTitle('Bohemian Rhapsody')
            ->setDuration(355)
            ->setAlbum('A Night at the Opera')
            ->setAuthor('Queen')
            ->setReplays(250000000)
            ->setLikes(5000000)
            ->setGenre($style)
            ->setFile("BohemianRhapsody");

        // Persistir entidades
        $this->entityManager->persist($style);
        $this->entityManager->persist($song);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Song created successfully!',
            'song' => [
                'title' => $song->getTitle(),
                'author' => $song->getAuthor(),
                'album' => $song->getAlbum(),
                'duration' => $song->getDuration(),
                'replays' => $song->getReplays(),
                'likes' => $song->getLikes(),
                'genre' => [
                    'name' => $style->getName(),
                    'description' => $style->getDescription()
                ]
            ]
        ]);
    }


    #[Route('/Song/{songName}/play', name: 'play_music', methods: ['GET'])]
    public function playMusic(string $songName): Response
    {
        // La carpeta mp3 está dentro de public/
        $musicDirectory = $this->getParameter('kernel.project_dir') . '/public/mp3/';
        $filePath = $musicDirectory . $songName . '.mp3';

        if (!file_exists($filePath)) {
            return new Response('Archivo no encontrado', 404);
        }

        return new BinaryFileResponse($filePath);
    }


    #[Route('/music', name: 'app_music')]
    public function index(): Response
    {
        return $this->render('play/play.html.twig', [
            'controller_name' => 'MusicController',
        ]);
    }



    //angular
    // src/Controller/SongController.php

// Ruta para obtener los likes de todas las canciones
#[Route('/songs/likes', name: 'get_song_likes', methods: ['GET'])]
public function getSongLikes(SongRepository $songRepository): JsonResponse
{
    $songs = $songRepository->findAll(); // Aquí recuperas todas las canciones

    $songLikes = array_map(function($song) {
        return [
            'id' => $song->getId(),
            'title' => $song->getTitle(),
            'likes' => $song->getLikes(),
        ];
    }, $songs);

    return $this->json($songLikes);
}


// Ruta para actualizar el like de una canción
#[Route('/songs/{id}/like', name: 'like_song', methods: ['POST'])]
public function likeSong(int $id, SongRepository $songRepository, EntityManagerInterface $em): JsonResponse
{
    $song = $songRepository->find($id);

    if (!$song) {
        return $this->json(['message' => 'Canción no encontrada'], 404);
    }

    // Incrementa el contador de likes
    $song->setLikes($song->getLikes() + 1);
    $em->flush();

    return $this->json(['likes' => $song->getLikes()]);
}

}
