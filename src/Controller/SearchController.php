<?php
// src/Controller/SearchController.php
namespace App\Controller;

use App\Repository\SongRepository;
use App\Repository\PlaylistRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /*$request->query es un objeto que contiene todos los parámetros de consulta de la URL.
     Por ejemplo, si la URL es algo como https://miapp.com/search?q=rock,
     entonces $request->query contendría los parámetros de consulta: ['q' => 'rock']*/ 
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, SongRepository $songRepo, PlaylistRepository $playlistRepo): JsonResponse
    {
        $query = $request->query->get('q');

        if (!$query) {
            return $this->json([
                'songs' => [],
                'playlists' => []
            ]);
        }

        $songs = $songRepo->findByTitleLike($query, 3);
        $playlists = $playlistRepo->findByNameLike($query, 3);

        return $this->json([
            'songs' => array_map(fn($song) => ['title' => $song->getTitle(), "fileTitle" => $song->getFile()], $songs),
            'playlists' => array_map(fn($playlist) => [
                'name' => $playlist->getName(),
                'songs' => array_map(fn($playlistSong) => [
                    'title' => $playlistSong->getSong()->getTitle(),
                    'fileTitle' => $playlistSong->getSong()->getFile()
                ], $playlist->getPlaylistSongs()->toArray())
            ], $playlists)
        ]);
        
        
    }
}
