<?php

namespace  App\Controller\Manager;
use App\Repository\SongRepository;
use App\Repository\PlaylistSongRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends AbstractController
{
    #[Route('/Manager', name: 'statistics')]
    public function index(): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_MANAGER', null, 'No tienes acceso a esta página.');


        return $this->render('Manager/statistics.html.twig');

    }
    #[Route('/statistics/data', name: 'statistics_data')]
    public function obtainData(PlaylistSongRepository $playlistSongRepository,UserRepository $UserRepository,
    SongRepository $SongRepository,SongRepository $obtainSongsStyleMostReplayedData): JsonResponse
    {
        //$this->denyAccessUnlessGranted('ROLE_MANAGER', null, 'No tienes acceso a esta página.');

         // most replayed playlists
    $obtainPlaylistMostReplaysData = $playlistSongRepository->obtainPlaylistMostReplaysData();

    // most liked playlists
    $obtainPlaylistMostLikesData = $playlistSongRepository->obtainPlaylistMostLikesData();

    //most replayed SONGS
    $obtainSongsMostReplayedData = $SongRepository->obtainSongsMostReplayedData();

    //Ages users
    $obtainUserAgeData = $UserRepository->obtainUserAgeData();

    
    $obtainSongsStyleMostReplayedData= $SongRepository ->obtainSongsStyleMostReplayedData();
    // Combina los datos en un array para enviarlos como respuesta
    $datos = [
        'playlist_most_replays' => $obtainPlaylistMostReplaysData,
        'playlist_most_likes' => $obtainPlaylistMostLikesData,
        'song_most_replays' => $obtainSongsMostReplayedData,
        'obtainUserAgeData' => $obtainUserAgeData,
        'obtainSongsStyleMostReplayedData' => $obtainSongsStyleMostReplayedData
    ];

    return $this->json($datos);
    }
}
