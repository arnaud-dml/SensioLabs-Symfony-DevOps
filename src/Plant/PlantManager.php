<?php

namespace App\Plant;

use App\Entity\Plant;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class PlantManager
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
     *
     * @return Plant
     */
    public function createFromArray(array $data): Plant
    {
        if (empty($data) || empty($data['date'])) {
            throw new MissingOptionsException();
        }
        $plant = new Plant();
        $plant->setDate($data['date']);
        $this->entityManager->persist($plant);
        $this->entityManager->flush();

        return $plant;
    }
}
