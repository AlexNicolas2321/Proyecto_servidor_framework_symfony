<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class UserActivityLogger
{
    private $logger;
    private $security;

    public function __construct(
        LoggerInterface $userActivityLogger,
        Security $security
    ) {
        $this->logger = $userActivityLogger;
        $this->security = $security;
    }

    public function logActivity(string $action)
    {
        $user = $this->security->getUser();
        $username = $user ? $user->getUserIdentifier() : 'anonymous';
        
        // Obtener el rol del usuario
        $role = 'anonymous';
        if ($user) {
            $roles = $user->getRoles();
            if (in_array('ROLE_ADMIN', $roles)) {
                $role = 'ADMIN';
            } elseif (in_array('ROLE_MANAGER', $roles)) {
                $role = 'MANAGER';
            } elseif (in_array('ROLE_USER', $roles)) {
                $role = 'USER';
            }
        }
        
        $this->logger->info(sprintf(
            '[%s] User "%s" performed action: %s',
            $role,
            $username,
            $action
        ));
    }

    // Métodos específicos para acciones CRUD------- y usuario crea playlist
    public function logCrudAction(string $action, string $entityType, string $entityName): void
    {
        $message = sprintf('%s %s: %s', $action, $entityType, $entityName);
        $this->logActivity($message);
    }
}