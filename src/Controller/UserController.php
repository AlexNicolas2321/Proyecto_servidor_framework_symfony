<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractController
{
    #[Route('/user/new', name: 'app_user')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        // Crear usuario
        $user = new User();
        $user->setEmail('user@example.com')
             ->setPassword('securepassword')
             ->setName('John Doe')
             ->setBirthDate(new \DateTime('1990-01-01'));

        // Crear perfil básico para el usuario
        $profile = new Profile();
        $profile->setPhoto('default_profile.jpg')
                ->setDescription('Perfil de usuario nuevo');

        // Establecer relación bidireccional
        $user->setProfile($profile);
        $profile->setUser($user);

        // Persistir entidades
        $entityManager->persist($profile);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'Usuario creado exitosamente',
            'data' => [
                'user' => [
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'birth_date' => $user->getBirthDate()->format('Y-m-d')
                ],
                'profile' => [
                    'photo' => $profile->getPhoto(),
                    'description' => $profile->getDescription()
                ]
            ]
        ]);
    }
}
