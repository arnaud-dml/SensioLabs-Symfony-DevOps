<?php

namespace App\Security\Handler;

use App\Common\Helper\LoggerTrait;
use App\Helper\MailerHelper;
use App\Manager\TokenManager;
use App\Repository\GardenerRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class LostPasswordHandler
{
    use LoggerTrait;
    
    /**
     * @var GardenerRepository
     */
    private $gardenerRepository;
    
    /**
     * @var TokenManager
     */
    private $tokenManager;
    
    /**
     * @var MailerHelper
     */
    private $mailerHelper;
    
    /**
     * @param GardenerRepository $gardenerRepository
     * @param TokenManager $tokenManager
     * @param MailerHelper $mailerHelper
     */
    public function __construct(
        GardenerRepository $gardenerRepository,
        TokenManager $tokenManager,
        MailerHelper $mailerHelper
    ) {
        $this->gardenerRepository = $gardenerRepository;
        $this->tokenManager = $tokenManager;
        $this->mailerHelper = $mailerHelper;
    }
    
    /**
     * @param FormInterface $form
     * @param Request $request
     * @return boolean
     */
    public function handle(FormInterface $form, Request $request)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $gardener = $this->gardenerRepository->findOneByUsernameOrEmail($form->getData()['login']);
            if ($gardener === null) {
                $form->addError(new FormError('Unknown account'));
                return false;
            }
            try {
                $token = $this->tokenManager->createLostPasswordToken($gardener);
                $this->mailerHelper->sendLostPasswordMail($token);
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
