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
        $this->logger = $userActivityLogger;  //escribir logs
        $this->security = $security;          //obetener info del usuario
    }

    public function logActivity(string $action) //ACción
    {
        $user = $this->security->getUser();
        $username = $user ? $user->getUserIdentifier() : 'anonymous';
        
        $this->logger->info(sprintf( //registrar la actividad
            'User "%s" performed action: %s',
            $username,
            $action
        ));
    }
}
