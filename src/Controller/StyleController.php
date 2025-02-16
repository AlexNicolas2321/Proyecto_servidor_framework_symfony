<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Style;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class StyleController extends AbstractController
{
    #[Route('/style/new', name: 'app_style')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        // Crear una nueva entidad de Style
        $style = new Style();
        $style->setName('musica');
        $style->setDescription('Estilo musical caracterizado por guitarras eléctricas y batería.');

        $entityManager->persist($style);
        

        // Hacer flush para guardar los cambios en la base de datos
        $entityManager->flush();

        return $this->json([
            'message' => 'Nuevo estilo de música creado exitosamente.',
            'style' => [
                'id' => $style->getId(),
                'name' => $style->getName(),
                'description' => $style->getDescription(),
            ],
            
        ]);
    }
}
