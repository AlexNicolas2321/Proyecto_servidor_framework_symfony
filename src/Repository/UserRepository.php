<?php
namespace App\Repository;

use Doctrine\DBAL\Connection;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function obtainUserAgeData(): array
    {
        $connection = $this->getEntityManager()->getConnection();
        $sql = 'SELECT TIMESTAMPDIFF(YEAR, u.Birth_date, CURRENT_DATE()) AS Age, COUNT(*) AS ageAmount
                FROM user u 
                GROUP BY Age 
                ORDER BY Age DESC'; // Ordenar de mayor a menor edad
        
        $stmt = $connection->executeQuery($sql);
        return $stmt->fetchAllAssociative();
    }
    
    
}
