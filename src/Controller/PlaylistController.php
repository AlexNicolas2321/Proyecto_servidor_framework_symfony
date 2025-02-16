<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class PlaylistController extends AbstractController
{
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
}
