<?php

namespace App\Security\Handler;

use App\Gardener\GardenerManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RegisterHandler
{
    /**
     * @var GardenerManager
     */
    private $gardenerManager;
    
    /**
     * @param GardenerManager $gardenerManager
     */
    public function __construct(GardenerManager $gardenerManager)
    {
        $this->gardenerManager = $gardenerManager;
    }
    
    /**
     * @param FormInterface $form
     * @param Request $request
     * @return boolean
     */
    public function handle(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->gardenerManager->create($form->getData());
            return true;
        }
        return false;
    }
}