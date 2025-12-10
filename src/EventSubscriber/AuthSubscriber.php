<?php

namespace App\EventSubscriber;

use App\Entity\AuthenticationLogs;
use App\Entity\User;
use App\Repository\AuthenticationLogsRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class AuthSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private AuthenticationLogsRepository $authenticationLogs,
        private RequestStack $requestStack,
    )
    {}

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();


        if (!$user instanceof User) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();
        $ipAddress = $request?->getClientIp() ?? 'unknown';
        $userAgent = $request?->headers->get('User-Agent') ?? 'unknown';

        // Persist session log to database
        $authAttempt = new AuthenticationLogs();
        $authAttempt->setUser($user);
        $authAttempt->setIpAdress($ipAddress);
        $authAttempt->setUserAgent($userAgent);
        $authAttempt->setAttemptedAt(new \DateTimeImmutable());

        $this->authenticationLogs->save($authAttempt,true);
    }
}
