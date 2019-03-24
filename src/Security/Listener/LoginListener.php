<?php

namespace App\Security\Listener;

use App\Gardener\GardenerManager;
use App\Repository\GardenerRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $gardenerRepository;

    /**
     * @var GardenerManager
     */
    private $gardenerManager;
    
    /**
     * @param GardenerRepository $gardenerRepository
     * @param GardenerManager $gardenerManager
     */
    public function __construct(GardenerRepository $gardenerRepository, GardenerManager $gardenerManager)
    {
        $this->gardenerRepository = $gardenerRepository;
        $this->gardenerManager = $gardenerManager;
    }

    /**
     * @return array
     * @see https://symfony.com/doc/current/components/security/authentication.html#authentication-events
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }

    /**
     * @param AuthenticationFailureEvent $event
     * @return void
     */
    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $errorMessage = $event->getAuthenticationException()->getMessage();
        if (strpos($errorMessage, 'checkCredentials()') !== false) {
            $username = $event->getAuthenticationToken()->getCredentials()['username'];
            $gardener = $this->gardenerRepository->findOneByUsernameOrEmail($username);
            $this->gardenerManager->login($gardener, false);
        }
    }

    /**
     * @param InteractiveLoginEvent $event
     * @return void
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $gardener = $event->getAuthenticationToken()->getUser();
        $this->gardenerManager->login($gardener, true);
    }
}
