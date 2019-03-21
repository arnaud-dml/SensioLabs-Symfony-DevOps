<?php

namespace App\Gardener;

use App\Entity\Gardener;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GardenerManager
{
    /**
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * @param ObjectManager $entityManager
     */
    public function __construct(ObjectManager $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param array $data
     *
     * @return Gardener
     */
    public function createFromArray(array $data): Gardener
    {
        if (empty($data) || (
            empty($data['username']) &&
            empty($data['email']) &&
            empty($data['plainPassword'])
        )) {
            throw new MissingOptionsException();
        }
        $gardener = new Gardener();
        $gardener->setUsername($data['username']);
        $gardener->setEmail($data['email']);
        $gardener->addRole('ROLE_USER');
        $this->entityManager->persist($gardener);
        $this->entityManager->flush();

        return $gardener;
    }

    /**
     * @param Gardener $gardener
     *
     * @return Gardener
     */
    public function create(Gardener $gardener): Gardener
    {
        $gardener->setPassword($this->passwordEncoder->encodePassword($gardener, $gardener->getPlainPassword()));
        $gardener->setRoles(['ROLE_USER']);
        $this->entityManager->persist($gardener);
        $this->entityManager->flush();
        return $gardener;
    }
}
