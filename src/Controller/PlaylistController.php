<?php

namespace App\Controller;


use App\Entity\Song;
use App\Entity\PlaylistSong;
use App\Entity\Playlist;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserActivityLogger;


final class PlaylistController extends AbstractController
{

    private $userActivityLogger;

    // Inyectamos el servicio UserActivityLogger en el constructor
    public function __construct(UserActivityLogger $userActivityLogger)
    {
        $this->userActivityLogger = $userActivityLogger;
    }


    #[Route('/playlist/new', name: 'app_playlist')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        // Crear usuario hardcoded
        $user = new User();
        $user->setName('John Doe')
             ->setEmail('john.doe@example.com')
             ->setPassword('password123')
             ->setBirthDate(new \DateTime('1990-01-01'));

        // Crear playlist
        $playlist = new Playlist();
        $playlist->setName('Mi Playlist')
            ->setVisibility('public')
            ->setReplays(0)
            ->setLikes(0)
            ->setOwner($user);

        // Persistir entidades
        $entityManager->persist($user);
        $entityManager->persist($playlist);
        $entityManager->flush();

        return $this->json([
            'message' => 'Playlist created successfully!',
            'playlist' => [
                'name' => $playlist->getName(),
                'visibility' => $playlist->getVisibility(),
                'owner' => [
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'birth_date' => $user->getBirthDate()->format('Y-m-d')
                ]
            ]
        ]);
    }





    #[Route('/api/playlists', name: 'get_user_playlists', methods: ['GET'])]
    public function getUserPlaylists(EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        
        // Verificar que el usuario está autenticado
        if (!$user || !$user instanceof User) {
            return new JsonResponse(['error' => 'No autorizado'], 401);
        }
    
        // Obtener el ID del usuario autenticado
        $userId = $user->getId();
    
        // Buscar playlists donde el ID del propietario coincida con el ID del usuario autenticado
        $playlists = $em->getRepository(Playlist::class)->findBy(['Owner' => $userId]);
    
        // Preparar las playlists junto con sus canciones y el fileName de la canción
        $data = array_map(fn($playlist) => [
            'id' => $playlist->getId(),
            'name' => $playlist->getName(),
            'visibility' => $playlist->getVisibility(),
            'replays' => $playlist->getReplays(),
            'likes' => $playlist->getLikes(),
            'canciones' => array_map(fn($playlistSong) => [
                'id' => $playlistSong->getId(),
                'title' => $playlistSong->getSong()->getTitle(), // Obtener título de la canción
                'fileTitle' => $playlistSong->getSong()->getFile(), // Obtener file de la canción
            ], $playlist->getPlaylistSongs()->toArray()), // Acceder a la relación con PlaylistSong
        ], $playlists);
    
        return new JsonResponse($data);
    }
    






// src/Controller/PlaylistController.php

#[Route('/api/create_playlists', name: 'create_playlist', methods: ['POST'])]
public function createPlaylist(Request $request, EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser(); // Obtener el usuario autenticado
    
    if (!$user || !$user instanceof User) {
        return new JsonResponse(['error' => 'No autorizado'], 401);
    }

    // Obtener datos enviados desde el frontend
    $data = json_decode($request->getContent(), true);

    // Validar si la información necesaria está presente
    if (empty($data['name']) || empty($data['songTitles'])) {
        return new JsonResponse(['error' => 'Nombre de playlist y canciones son requeridos'], 400);
    }

    // Crear una nueva playlist
    $playlist = new Playlist();
    $playlist->setName($data['name']);
    $playlist->setVisibility(true); // Siempre true
    $playlist->setReplays(0); // Inicializa en 0
    $playlist->setLikes(0); // Inicializa en 0
    $playlist->setOwner($user); // ✅ Correcto
 // Asignar el ID del usuario como propietario

    // Obtener los títulos de las canciones
    $songTitles = $data['songTitles'];

    // Buscar las canciones por título
    $songs = $em->getRepository(Song::class)->findBy(['Title' => $songTitles]);

    // Añadir las canciones a la playlist
    foreach ($songs as $song) { 
        $playlistSong = new PlaylistSong(); // Crear una nueva relación
        $playlistSong->setSong($song); // Asignar la canción a la relación
        $playlistSong->setPlaylist($playlist); // Asignar la playlist a la relación
        $playlist->addPlaylistSong($playlistSong); // Añadir correctamente
        $em->persist($playlistSong); // Guardar la relación en la base de datos
    }

    // Persistir la playlist en la base de datos
    $em->persist($playlist);
    $em->flush();


    //guardar en el log 
    $this->userActivityLogger->logCrudAction('Created', 'Playlist', $playlist->getName());

    // Responder con un mensaje
    return new JsonResponse(['message' => 'Playlist creada con éxito'], 201); // Código de éxito 201 para creación
}










    
}
