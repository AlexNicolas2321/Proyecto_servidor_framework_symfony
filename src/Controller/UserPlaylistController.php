<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Playlist;
use App\Entity\UserPlaylist;
use App\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

final class UserPlaylistController extends AbstractController
{
    #[Route('/user/playlist/new', name: 'app_user_playlist')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        // Crear perfil
        $profile = new Profile();
        $profile->setPhoto('default_photo.jpg')
                ->setDescription('Perfil de usuario creado automáticamente.');

        // Crear usuario
        $user = new User();
        $user->setEmail('johndoe@example.com')
             ->setPassword('securepassword')
             ->setName('John Doe')
             ->setBirthDate(new \DateTime('1990-01-01'))
             ->setProfile($profile);

        // Crear playlist
        $playlist = new Playlist();
        $playlist->setName('Rock Playlist')
                ->setVisibility('Public')
                ->setReplays(0)
                ->setLikes(0)
                ->setOwner($user);

        // Crear la relación UserPlaylist
        $userPlaylist = new UserPlaylist();
        $userPlaylist->setUsers($user)
                    ->setPlaylists($playlist)
                    ->setReproductions(5);

        // Persistir entidades
        $entityManager->persist($profile);
        $entityManager->persist($user);
        $entityManager->persist($playlist);
        $entityManager->persist($userPlaylist);
        $entityManager->flush();

        return $this->json([
            'message' => 'UserPlaylist creada exitosamente',
            'data' => [
                'user' => [
                    'name' => $user->getName(),
                    'email' => $user->getEmail()
                ],
                'playlist' => [
                    'name' => $playlist->getName(),
                    'visibility' => $playlist->getVisibility()
                ],
                'reproductions' => $userPlaylist->getReproductions()
            ]
        ]);
    }
}
