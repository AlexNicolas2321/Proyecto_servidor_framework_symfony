<?php

namespace App\Repository;

use App\Entity\PlaylistSong;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlaylistSong>
 */
class PlaylistSongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlaylistSong::class);
    }

    public function obtainPlaylistMostReplaysData(): array
{
    return $this->createQueryBuilder('pc')
        ->select('p.Name AS Playlist, p.Replays AS Replays')
        ->join('pc.playlist', 'p') // INNER JOIN playlist p ON pc.playlist_id = p.id
        ->orderBy('p.Replays', 'DESC') // Ordenar por número de reproducciones de mayor a menor
        ->getQuery()
        ->getResult();
}

public function obtainPlaylistMostLikesData(): array
{
    return $this->createQueryBuilder('pc')
        ->select('p.Name AS Playlist, p.Likes AS Likes')
        ->join('pc.playlist', 'p') // INNER JOIN playlist p ON pc.playlist_id = p.id
        ->orderBy('p.Likes', 'DESC') // Ordenar por número de likes de mayor a menor
        ->getQuery()
        ->getResult();
}

   
  
    
    //    /**
    //     * @return PlaylistSong[] Returns an array of PlaylistSong objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PlaylistSong
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
