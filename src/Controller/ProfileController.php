<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Style;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile/new', name: 'app_profile')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        // Crear una nueva entidad de Profile
        $profile = new Profile();
        $profile->setPhoto('profile_photo.jpg')
                ->setDescription('Este es un perfil de ejemplo.');

        // Crear y asociar algunos estilos musicales al perfil
        $style1 = new Style();
        $style1->setName('Rock')
               ->setDescription('Género musical con guitarras eléctricas.');

        $style2 = new Style();
        $style2->setName('Pop')
               ->setDescription('Género musical popular.');

        // Agregar los estilos al perfil
        $profile->addPreferedStyle($style1);
        $profile->addPreferedStyle($style2);

        // Crear y asociar un usuario al perfil
        $user = new User();
        $user->setEmail('user@example.com')
             ->setName('John Doe')
             ->setPassword('password123')
             ->setBirthDate(new \DateTime('1990-01-01'));

        $profile->setUser($user);
        $user->setProfile($profile);

        // Persistir las entidades
        $entityManager->persist($style1);
        $entityManager->persist($style2);
        $entityManager->persist($user);
        $entityManager->persist($profile);
        $entityManager->flush();

        return $this->json([
            'message' => 'Perfil y datos asociados creados exitosamente.',
            'profile' => [
                'photo' => $profile->getPhoto(),
                'description' => $profile->getDescription(),
                'preferred_styles' => [
                    ['name' => $style1->getName()],
                    ['name' => $style2->getName()]
                ],
                'user' => [
                    'name' => $user->getName(),
                    'email' => $user->getEmail()
                ]
            ]
        ]);
    }
}
