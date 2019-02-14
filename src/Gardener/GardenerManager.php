<?php

namespace App\Gardener;

use App\Entity\Gardener;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class GardenerManager
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
     * @return Gardener
     */
    public function createFromArray(array $data): Gardener
    {
        if (empty($data) || (empty($data['username']) && empty($data['email']))) {
            throw new MissingOptionsException();
        }
        $gardener = new Gardener();
        $gardener->setUsername($data['username']);
        $gardener->setEmail($data['email']);
        $this->entityManager->persist($gardener);
        $this->entityManager->flush();

        return $gardener;
    }
}
