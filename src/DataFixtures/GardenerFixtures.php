<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Gardener;

class GardenerFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    public $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $gardener = new Gardener();
        $gardener->setUsername('johndoe');
        $gardener->setEmail('johndoe@gmail.com');
        $gardener->setPassword($this->passwordEncoder->encodePassword($gardener, 'johndoe'));
        $gardener->addRole('ROLE_USER');
        $manager->persist($gardener);
        $manager->flush();
    }
}
