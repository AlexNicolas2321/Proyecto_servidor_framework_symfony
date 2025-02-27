<?php
namespace App\Repository;

use Doctrine\DBAL\Connection;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
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

    public function loadUserByIdentifier(string $identifier): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.Email = :identifier')
            ->setParameter('identifier', $identifier)
            ->getQuery()
            ->getOneOrNullResult();
    }
    

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException();
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
