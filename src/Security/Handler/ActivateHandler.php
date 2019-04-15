<?php

namespace App\Security\Handler;

use App\Common\Helper\LoggerTrait;
use App\Gardener\GardenerManager;
use App\Manager\TokenManager;
use App\Repository\TokenRepository;

class ActivateHandler
{
    use LoggerTrait;

    /**
     * @var GardenerManager
     */
    private $gardenerManager;

    /**
     * @var TokenManager
     */
    private $tokenManager;

    /**
     * @var TokenRepository
     */
    private $tokenRepository;

    /**
     * @param GardenerManager $gardenerManager
     * @param TokenManager    $tokenManager
     * @param TokenRepository $tokenRepository
     */
    public function __construct(
        GardenerManager $gardenerManager,
        TokenManager $tokenManager,
        TokenRepository $tokenRepository
    ) {
        $this->gardenerManager = $gardenerManager;
        $this->tokenManager = $tokenManager;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function handle(string $token)
    {
        $token = $this->tokenRepository->findOneBy([
            'token' => $token,
            'type' => TokenManager::TOKEN_TYPE_REGISTER,
        ]);
        if (null === $token) {
            return false;
        }
        try {
            $this->gardenerManager->save($token->getGardener()->setEnabled(true));
            $this->tokenManager->delete($token);
        } catch (\Exception $e) {
            $this->logError($e->getMessage());

            return false;
        }

        return true;
    }
}
