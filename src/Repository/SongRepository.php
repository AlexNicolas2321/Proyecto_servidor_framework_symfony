<?php

namespace App\Repository;

use App\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Song>
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }

   public function obtainSongsMostReplayedData(): array
{
    return $this->createQueryBuilder('c')
        ->select('c.Title AS Song, c.Replays AS Replays')
        ->orderBy('c.Replays', 'DESC') // Ordenar por número de reproducciones de mayor a menor
        ->getQuery()
        ->getResult();
}


    public function obtainSongsStyleMostReplayedData(): array
    {
        return $this->createQueryBuilder('song')
            ->select('style.Name AS Style, SUM(song.Replays) AS StyleSongReplays')
            ->join('song.Genre', 'style') //INNER JOIN Song on Style
            ->groupBy("style.Name")
            ->orderBy("StyleSongReplays", "DESC") 
            ->getQuery()
            ->getResult();
    }

    // src/Repository/SongRepository.php
public function findByTitleLike(string $query, int $limit)
{
    return $this->createQueryBuilder('s')
        ->where('s.Title LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}

    //    /**
    //     * @return Song[] Returns an array of Song objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Song
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
