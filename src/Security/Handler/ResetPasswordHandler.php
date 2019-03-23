<?php

namespace App\Security\Handler;

use App\Common\Helper\LoggerTrait;
use App\Entity\Token;
use App\Gardener\GardenerManager;
use App\Manager\TokenManager;
use App\Repository\TokenRepository;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class ResetPasswordHandler
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
     * @param TokenManager $tokenManager
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
     * @param String $token
     * @return boolean
     */
    public function handle(FormInterface $form, Request $request, string $token)
    {
        $token = $this->tokenRepository->findOneBy([
            'token' => $token,
            'type' => TokenManager::TOKEN_TYPE_LOST_PASSWORD
        ]);
        if ($token === null) {
            $form->addError(new FormError("Unknown account"));
            return false;
        }
        if ($token->isExpired()) {
            $form->addError(new FormError("This link has expired"));
            return false;
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->gardenerManager->encodePassword(
                $token
                    ->getGardener()
                    ->setPlainPassword(
                        $form->getData()['plainPassword']
                    )
            );
            try {
                $this->gardenerManager->save($token->getGardener());
                $this->tokenManager->delete($token);
            } catch (Exception $e) {
                $this->logError($e->getMessage());
                $form->addError(new FormError('Sorry, we encountered an error'));
                return false;
            }
            return true;
        }
        return false;
    }
}
