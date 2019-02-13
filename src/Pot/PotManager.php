<?php

namespace App\Pot;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use App\Entity\Pot;

class PotManager
{
    /** 
     * @var ObjectManager
     */
    protected $entityManager;


    /**
     * @param ObjectManager $entityManager
     */
    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $data
     * @return Pot
     */
    public function createFromArray(array $data): Pot
    {
        if (empty($data) || empty($data['location'])) {
            throw new MissingOptionsException();
        }
        $pot = new Pot();
        $pot->setLocation($data['location']);
        $this->entityManager->persist($pot);
        $this->entityManager->flush();
        return $pot;
    }
}