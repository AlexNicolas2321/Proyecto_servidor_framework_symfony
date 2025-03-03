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
        $request->getSession()->set('user_logged_in', true);  // Marca la sesión como iniciada
        $request->getSession()->set('email', $request->request->get('email'));
        // Verificar el rol del usuario y redirigir a la página correspondiente
        if ($user->getRoles('ROLE_ADMIN')) {
            return new RedirectResponse($this->router->generate('admin'));
        } elseif ($user->getRoles('ROLE_MANAGER')) {
            return new RedirectResponse($this->router->generate('statistics'));
        }
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
    }
    public function logout(Request $request)
    {
        // Eliminar la sesión del usuario
        $request->getSession()->invalidate();  // Esto destruye la sesión
        return new RedirectResponse($this->router->generate('login'));

    }
}
