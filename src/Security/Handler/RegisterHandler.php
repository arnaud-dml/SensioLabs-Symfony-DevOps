<?php

namespace App\Security\Handler;

use App\Gardener\GardenerManager;
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
     * @param GardenerManager $gardenerManager
     */
    public function __construct(GardenerManager $gardenerManager, TokenManager $tokenManager)
    {
        $this->gardenerManager = $gardenerManager;
        $this->tokenManager = $tokenManager;
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
                $this->tokenManager->register($gardener);
            } catch (Exception $e) {
                $this->logError($e->getMessage());
                return false;
            }
            return true;
        }
        return false;
    }
}
