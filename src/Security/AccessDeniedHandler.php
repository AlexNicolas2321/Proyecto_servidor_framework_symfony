<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $twig;
    private $security;

    public function __construct(Environment $twig, Security $security)
    {
        $this->twig = $twig;
        $this->security = $security;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        // Obtener el rol actual del usuario
        $user = $this->security->getUser();
        $currentRole = $user ? $user->getRoles()[0] : 'No estás logueado';

        // Obtener la ruta a la que intentó acceder
        $route = $request->getPathInfo();
        
        // Determinar qué rol se necesita basado en la ruta
        $neededRole = match(true) {
            str_starts_with($route, '/admin') => 'ROLE_ADMIN',
            str_starts_with($route, '/home') => 'ROLE_USER',
            str_starts_with($route, '/Manager') => 'ROLE_MANAGER',
            default => 'rol desconocido'
        };

        // Renderizar una plantilla personalizada
        return new Response(
            $this->twig->render('security/access_denied.html.twig', [
                'needed_role' => $neededRole,
                'current_role' => $currentRole,
            ]),
            Response::HTTP_FORBIDDEN
        );
    }
}