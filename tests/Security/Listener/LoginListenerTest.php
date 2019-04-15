<?php

namespace App\Tests\Security\Listener;

use App\Entity\Gardener;
use App\Gardener\GardenerManager;
use App\Repository\GardenerRepository;
use App\Security\Listener\LoginListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LoginListenerTest extends TestCase
{
    /**
     * @var MockObject|GardenerRepository
     */
    private $gardenerRepository;

    /**
     * @var MockObject|GardenerManager
     */
    private $gardenerManager;

    /**
     * @var MockObject|AuthenticationFailureEvent
     */
    private $authenticationFailureEvent;

    /**
     * @var MockObject|InteractiveLoginEvent
     */
    private $interactiveLoginEvent;

    /**
     * @var LoginListener
     */
    private $listener;

    protected function setUp()
    {
        $this->gardenerRepository = $this->createMock(GardenerRepository::class);
        $this->gardenerManager = $this->createMock(GardenerManager::class);
        $this->authenticationFailureEvent = $this->createMock(AuthenticationFailureEvent::class);
        $this->interactiveLoginEvent = $this->createMock(InteractiveLoginEvent::class);
        $this->listener = new LoginListener(
            $this->gardenerRepository,
            $this->gardenerManager
        );
    }

    public function testGetSubscribedEvents()
    {
        self::assertArrayHasKey(
            AuthenticationEvents::AUTHENTICATION_FAILURE,
            $this->listener->getSubscribedEvents()
        );
        self::assertEquals(
            'onAuthenticationFailure',
            $this->listener->getSubscribedEvents()[
                AuthenticationEvents::AUTHENTICATION_FAILURE
            ]
        );

        self::assertArrayHasKey(
            SecurityEvents::INTERACTIVE_LOGIN,
            $this->listener->getSubscribedEvents()
        );
        self::assertEquals(
            'onInteractiveLogin',
            $this->listener->getSubscribedEvents()[
                SecurityEvents::INTERACTIVE_LOGIN
            ]
        );
    }

    // public function testOnAuthenticationFailure()
    // {
    //     $this->authenticationFailureEvent
    //         ->expects(self::once())
    //         ->method('getAuthenticationException')
    //         ->willReturn(new AuthenticationException('checkCredentials()'));

    //     $this->authenticationFailureEvent
    //         ->expects(self::once())
    //         ->method('getAuthenticationToken');

    //     $this->gardenerRepository
    //         ->expects(self::once())
    //         ->method('findOneByUsernameOrEmail')
    //         ->will('username')
    //         ->willReturn(new Gardener());

    //     $this->gardenerManager
    //         ->expects(self::once())
    //         ->method('login');

    //     $this->listener->onAuthenticationFailure($this->authenticationFailureEvent);
    // }

    // public function testOnInteractiveLogin()
    // {
    //     $this->listener->onInteractiveLogin($this->interactiveLoginEvent);
    // }
}
