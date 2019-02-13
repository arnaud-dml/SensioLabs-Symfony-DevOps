<?php

namespace App\PlantType;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use App\Entity\PlantType;

class PlantTypeManager
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
     * @return PlantType
     */
    public function createFromArray(array $data): PlantType
    {
        if (empty($data) || empty($data['name'])) {
            throw new MissingOptionsException();
        }
        $plantType = new PlantType();
        $plantType->setName($data['name']);
        $this->entityManager->persist($plantType);
        $this->entityManager->flush();
        return $plantType;
    }
}
