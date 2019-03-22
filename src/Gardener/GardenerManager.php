<?php

namespace App\Gardener;

use App\Common\Helper\LoggerTrait;
use App\Entity\Gardener;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GardenerManager
{
    use LoggerTrait;

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
     * @throws MissingOptionsException
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
        $gardener->setPlainPassword($data['plainPassword']);
        $gardener->addRole('ROLE_USER');
        $gardener->setEnabled(false);
        $this->encodePassword($gardener);
        $this->save($gardener);
        return $gardener;
    }

    /**
     * @param Gardener $gardener
     * @throws MissingOptionsException
     * @return Gardener
     */
    public function encodePassword(Gardener $gardener): Gardener
    {
        if ($gardener->getPlainPassword()) {
            $password = $this->passwordEncoder->encodePassword(
                $gardener, 
                $gardener->getPlainPassword()
            );
            $gardener->setPassword($password);
        } else {
            throw new MissingOptionsException();
        }
        return $gardener;
    }

    /**
     * @param Gardener $gardener
     * @return Gardener
     */
    public function register(Gardener $gardener): Gardener
    {
        $data = [];
        $data['username'] = $gardener->getUsername();
        $data['email'] = $gardener->getEmail();
        $data['plainPassword'] = $gardener->getPlainPassword();
        return $this->createFromArray($data);
    }

    /**
     * @param Gardener $gardener
     * @throws ORMException
     * @return bool
     */
    public function save(Gardener $gardener): bool
    {
        try {
            $this->entityManager->persist($gardener);
        } catch (ORMException $e) {
            $this->logError($e->getMessage());
            return false;
        }
        $this->entityManager->flush();
        return true;
    }
}
