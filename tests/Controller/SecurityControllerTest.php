<?php

namespace App\Tests\Controller;

use App\Security\Handler\ActivateHandler;
use App\Security\Handler\LostPasswordHandler;
use App\Security\Handler\RegisterHandler;
use App\Security\Handler\ResetPasswordHandler;
use App\Tests\Common\AuthClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use AuthClientTrait;

    private $client = null;

    /**
     * @var MockObject|ActivateHandler
     */
    private $activateHandler;

    /**
     * @var MockObject|LostPasswordHandler
     */
    private $lostPasswordHandler;

    /**
     * @var MockObject|RegisterHandler
     */
    private $registerHandler;

    /**
     * @var MockObject|ResetPasswordHandler
     */
    private $resetPasswordHandler;

    public function setUp()
    {
        $this->client = static::createClient();

        $this->activateHandler = $this->createMock(ActivateHandler::class);
        $this->lostPasswordHandler = $this->createMock(LostPasswordHandler::class);
        $this->registerHandler = $this->createMock(RegisterHandler::class);
        $this->resetPasswordHandler = $this->createMock(ResetPasswordHandler::class);

        $this->client->getContainer()->set('App\Security\Handler\ActivateHandler', $this->activateHandler);
        $this->client->getContainer()->set('App\Security\Handler\LostPasswordHandler', $this->lostPasswordHandler);
        $this->client->getContainer()->set('App\Security\Handler\RegisterHandler', $this->registerHandler);
        $this->client->getContainer()->set('App\Security\Handler\ResetPasswordHandler', $this->resetPasswordHandler);
    }

    public function testLogin()
    {
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        $crawler = $this->client->request('GET', $route);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertCount(1, $crawler->filter('form'));
        self::assertCount(1, $crawler->filter('input[name=_username]'));
        self::assertCount(1, $crawler->filter('input[name=_password]'));
        self::assertCount(1, $crawler->filter('input[name=_remember_me]'));
        self::assertCount(1, $crawler->filter('input[name=_csrf_token]'));
        self::assertCount(1, $crawler->filter('button[type=submit]'));
    }

    public function testLoginAlreadyAuthenticated()
    {
        $this->authClient($this->client);
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        $this->client->request('GET', $route);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('homepage');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }

    public function testLogout()
    {
        $this->authClient($this->client);
        $route = $this->client->getContainer()->get('router')->generate('security_logout');
        $this->client->request('GET', $route);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('security_login');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }

    public function testRegister()
    {
        $route = $this->client->getContainer()->get('router')->generate('security_register');
        $crawler = $this->client->request('GET', $route);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertCount(1, $crawler->filter('form[name=register]'));
        self::assertCount(1, $crawler->filter('#register__token'));
        self::assertCount(1, $crawler->filter('#register_username'));
        self::assertCount(1, $crawler->filter('#register_email'));
        self::assertCount(1, $crawler->filter('#register_plainPassword_first'));
        self::assertCount(1, $crawler->filter('#register_plainPassword_second'));
        self::assertCount(1, $crawler->filter('#register_submit'));
    }

    public function testRegisterSubmit()
    {
        $this->registerHandler
            ->expects(self::once())
            ->method('handle')
            ->willReturn(true);

        $route = $this->client->getContainer()->get('router')->generate('security_register');
        $this->client->request('GET', $route);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $route = $this->client->getContainer()->get('router')->generate('security_login');
        $location = $this->client->getResponse()->headers->get('location');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }

    public function testAccountActivation()
    {
        $route = $this->client->getContainer()->get('router')
            ->generate('security_account_activation', [
                'token' => 'OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc',
            ]);
        $this->client->request('GET', $route);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('location');
        $route = $this->client->getContainer()->get('router')->generate('homepage');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }

    public function testAccountActivationSubmit()
    {
        $this->activateHandler
            ->expects(self::once())
            ->method('handle')
            ->willReturn(true);

        $route = $this->client->getContainer()->get('router')
            ->generate('security_account_activation', [
                'token' => 'OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc',
            ]);
        $this->client->request('GET', $route);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $route = $this->client->getContainer()->get('router')->generate('security_login');
        $location = $this->client->getResponse()->headers->get('location');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }

    public function testLostPassword()
    {
        $route = $this->client->getContainer()->get('router')->generate('security_lost_password');
        $crawler = $this->client->request('GET', $route);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertCount(1, $crawler->filter('form[name=lost_password]'));
        self::assertCount(1, $crawler->filter('#lost_password__token'));
        self::assertCount(1, $crawler->filter('#lost_password_login'));
        self::assertCount(1, $crawler->filter('#lost_password_submit'));
    }

    public function testLostPasswordSubmit()
    {
        $this->lostPasswordHandler
            ->expects(self::once())
            ->method('handle')
            ->willReturn(true);

        $route = $this->client->getContainer()->get('router')->generate('security_lost_password');
        $this->client->request('GET', $route);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $route = $this->client->getContainer()->get('router')->generate('security_login');
        $location = $this->client->getResponse()->headers->get('location');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }

    public function testResetPassword()
    {
        $route = $this->client->getContainer()->get('router')
            ->generate('security_reset_password', [
                'token' => 'OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc',
            ]);
        $this->client->request('GET', $route);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testResetPasswordSubmit()
    {
        $this->resetPasswordHandler
            ->expects(self::once())
            ->method('handle')
            ->willReturn(true);

        $route = $this->client->getContainer()->get('router')
            ->generate('security_reset_password', [
                'token' => 'OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc',
            ]);
        $this->client->request('GET', $route);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $route = $this->client->getContainer()->get('router')->generate('security_login');
        $location = $this->client->getResponse()->headers->get('location');
        self::assertRegExp('/'.\preg_quote($route, '/').'$/', $location);
    }
}
