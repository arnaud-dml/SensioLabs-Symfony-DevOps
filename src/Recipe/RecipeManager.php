<?php

namespace App\Recipe;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use App\Entity\Recipe;

class RecipeManager
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
     * @return Recipe
     */
    public function createFromArray(array $data): Recipe
    {
        if (empty($data) || (empty($data['optimalTemperature']) && empty($data['optimalHydrometry']))) {
            throw new MissingOptionsException();
        }
        $recipe = new Recipe();
        $recipe->setOptimalTemperature($data['optimalTemperature']);
        $recipe->setOptimalHydrometry($data['optimalHydrometry']);
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();
        return $recipe;
    }
}
