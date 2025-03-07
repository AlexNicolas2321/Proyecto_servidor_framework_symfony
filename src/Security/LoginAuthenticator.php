<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials as CredentialsInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Repository\UserRepository; // Actualizado a UserRepository

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private RouterInterface $router;
    private UserRepository $userRepository; // Cambiado a UserRepository

    public function __construct(RouterInterface $router, UserRepository $userRepository)
    {
        $this->router = $router;
        $this->userRepository = $userRepository; // Inicializado correctamente el repositorio
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // Buscar al usuario por correo
        $user = $this->userRepository->findOneByEmail($email);

        if (!$user) {
            throw new AuthenticationException('Credenciales incorrectas.');
        }

        // Verificar si la contraseña es válida
        if (!password_verify($password, $user->getPassword())) {
            throw new AuthenticationException('Las credenciales no coinciden.');
        }

        return new Passport(
            new UserBadge($email),
            new CredentialsInterface($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        
        $user = $token->getUser();
        // Verificar que el usuario no tenga el rol ROLE_ADMIN ni ROLE_MANAGER
    if (!in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_MANAGER', $user->getRoles())) {
        // Guardar el estado de la sesión y el nombre de usuario solo si es un usuario regular
        $request->getSession()->set('user_logged_in', true);
        $request->getSession()->set('email', $user->getUserIdentifier());
    }

        // Verificar el rol del usuario y redirigir
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('admin'));
        } elseif (in_array('ROLE_MANAGER', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('statistics'));
        }
        
        // Redirección por defecto
        return new RedirectResponse($this->router->generate('home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
    }

    public function logout(Request $request): Response
    {
        // Eliminar variables específicas de sesión
        $request->getSession()->remove('is_logged_in');
        $request->getSession()->remove('username');
        // Invalidar la sesión completa
        $request->getSession()->invalidate();
        
        return new RedirectResponse($this->router->generate('app_login'));
    }
}
