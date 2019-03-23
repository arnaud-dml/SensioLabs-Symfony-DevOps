<?php

namespace App\Security\Handler;

use App\Gardener\GardenerManager;
use App\Helper\MailerHelper;
use App\Manager\TokenManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RegisterHandler
{
    /**
     * @var GardenerManager
     */
    private $gardenerManager;

    /**
     * @var TokenManager
     */
    private $tokenManager;

    /**
     * @var MailerHelper
     */
    private $mailerHelper;
    
    /**
     * @param GardenerManager $gardenerManager
     */
    public function __construct(
        GardenerManager $gardenerManager,
        TokenManager $tokenManager,
        MailerHelper $mailerHelper
    ) {
        $this->gardenerManager = $gardenerManager;
        $this->tokenManager = $tokenManager;
        $this->mailerHelper = $mailerHelper;
    }
    
    /**
     * @param FormInterface $form
     * @param Request $request
     * @throws Exception
     * @return boolean
     */
    public function handle(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $gardener = $this->gardenerManager->register($form->getData());
                $token = $this->tokenManager->register($gardener);
                $this->mailerHelper->register($token);
            } catch (Exception $e) {
                $this->logError($e->getMessage());
                return false;
            }
            return true;
        }
        return false;
    }
}
