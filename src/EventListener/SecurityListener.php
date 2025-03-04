<?php

namespace App\EventListener;

use App\Service\UserActivityLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class SecurityListener implements EventSubscriberInterface
{
    private $userActivityLogger;

    public function __construct(UserActivityLogger $userActivityLogger)
    {
        $this->userActivityLogger = $userActivityLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InteractiveLoginEvent::class => 'onLogin',
            LogoutEvent::class => 'onLogout',
        ];
    }

    public function onLogin(InteractiveLoginEvent $event): void
    {
        $this->userActivityLogger->logActivity('user logged in');
    }

    public function onLogout(LogoutEvent $event): void
    {
        $this->userActivityLogger->logActivity('user logged out');
    }
}
