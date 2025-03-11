<?php
namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\Song;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


final class IndexController extends AbstractController
{
    private $entityManager;

    // Constructor para inyectar el EntityManager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso a esta página.');
        // Obtener todas las canciones de la base de datos
        $songs = $this->entityManager->getRepository(Song::class)->findAll();

        // Crear un array para almacenar los datos de las canciones
        $songData = [];

        foreach ($songs as $song) {
            $songData[] = [
                'id' => $song->getId(),
                'fileTitle' => $song->getFile(), // Almacena el título seguro
                'title' => $song->getTitle(),
            ];
        }

        $playLists = $this->entityManager->getRepository(Playlist::class)->findBy(['Owner' => null]);

        $playListData = [];

        foreach ($playLists as $playlist) {
            // Obtener las canciones de la lista de reproducción
            $playlistSongs = $playlist->getPlaylistSongs(); // Esto devuelve una colección de canciones

            $songsData = []; // Cambia el nombre a songsData para mayor claridad
            foreach ($playlistSongs as $playlistSong) {
                $song = $playlistSong->getSong(); // Obtener la canción asociada
                if ($song) {
                    $songsData[] = [
                        'id' => $song->getId(), // Obtener el ID de la canción
                        'title' => $song->getTitle(), // Obtener el título de la canción
                        'fileTitle' => $song->getFile(), // Almacena el título seguro
                    ];
                }
            }

            $playListData[] = [
                'id' => $playlist->getId(),
                'name' => $playlist->getName(), // Suponiendo que tienes un método getName()
                'songs' => $songsData, // Almacena los datos de las canciones en la lista de reproducción
            ];
        }
        
        return $this->render('index.html.twig', [
            'songs' => $songData,
            'playList' => $playListData, // Pasar el array de listas de reproducción a la vista
        ]);
    }


    #[Route('/angular', name: 'home1')]
    public function angular(): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso a esta página.');
        // Obtener todas las canciones de la base de datos
        $songs = $this->entityManager->getRepository(Song::class)->findAll();

        // Crear un array para almacenar los datos de las canciones
        $songData = [];

        foreach ($songs as $song) {
            $songData[] = [
                'id' => $song->getId(),
                'fileTitle' => $song->getFile(), // Almacena el título seguro
                'title' => $song->getTitle(),
            ];
        }

        $playLists = $this->entityManager->getRepository(Playlist::class)->findBy(['Owner' => null]);

        $playListData = [];

        foreach ($playLists as $playlist) {
            // Obtener las canciones de la lista de reproducción
            $playlistSongs = $playlist->getPlaylistSongs(); // Esto devuelve una colección de canciones

            $songsData = []; // Cambia el nombre a songsData para mayor claridad
            foreach ($playlistSongs as $playlistSong) {
                $song = $playlistSong->getSong(); // Obtener la canción asociada
                if ($song) {
                    $songsData[] = [
                        'id' => $song->getId(), // Obtener el ID de la canción
                        'title' => $song->getTitle(), // Obtener el título de la canción
                        'fileTitle' => $song->getFile(), // Almacena el título seguro
                    ];
                }
            }

            $playListData[] = [
                'id' => $playlist->getId(),
                'name' => $playlist->getName(), // Suponiendo que tienes un método getName()
                'songs' => $songsData, // Almacena los datos de las canciones en la lista de reproducción
            ];
        }
        
        return new JsonResponse([
            'songs' => $songData,
            'playList' => $playListData
        ]);
    }
}
