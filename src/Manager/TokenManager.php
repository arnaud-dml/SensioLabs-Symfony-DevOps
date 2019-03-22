<?php

namespace App\Manager;

use App\Common\Helper\LoggerTrait;
use App\Entity\Gardener;
use App\Entity\Token;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\ORMException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class TokenManager
{
    const TOKEN_TYPE_REGISTER = 'REGISTER';
    const TOKEN_TYPE_LOST_PASSWORD = 'LOST_PASSWORD';
    const TOKEN_DURATION_REGISTER = 'now +1 day';
    const TOKEN_DURATION_LOST_PASSWORD = 'now +10 min';

    use LoggerTrait;

    /**
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @var TokenGeneratorInterface
     */
    protected $tokenGenerator;

    /**
     * @param ObjectManager $entityManager
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function __construct(ObjectManager $entityManager, TokenGeneratorInterface $tokenGenerator)
    {
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
    }
    
    /**
     * @param array $data
     * @throws MissingOptionsException
     * @return Token
     */
    public function createFromArray(array $data): Token
    {
        if (empty($data) || (
            empty($data['gardener']) &&
            empty($data['type']) 
        )) {
            throw new MissingOptionsException();
        }
        $token = new Token();
        $token->setToken($this->tokenGenerator->generateToken());
        $token->setGardener($data['gardener']);
        $token->setType($data['type']);
        if ($data['expired_at']) {
            $token->setExpiredAt(new \Datetime($data['expired_at']));
        }
        $this->save($token);
        return $token;
    }

    /**
     * @param Gardener $gardener
     * @return Token
     */
    public function register(Gardener $gardener): Token
    {
        $data = [];
        $data['gardener'] = $gardener;
        $data['type'] = self::TOKEN_TYPE_REGISTER;
        $data['expired_at'] = self::TOKEN_DURATION_REGISTER;
        return $this->createFromArray($data);
    }

    /**
     * @param Gardener $gardener
     * @return Token
     */
    public function lostPassword(Gardener $gardener): Token
    {
        $data = [];
        $data['gardener'] = $gardener;
        $data['type'] = self::TOKEN_TYPE_LOST_PASSWORD;
        $data['expired_at'] = self::TOKEN_DURATION_LOST_PASSWORD;
        return $this->createFromArray($data);
    }
    
    /**
     * @param Token $token
     * @throws ORMException
     * @return bool
     */
    public function save(Token $token): ?bool
    {
        try {
            $this->entityManager->persist($token);
        } catch (ORMException $e) {
            $this->logError($e->getMessage());
            return false;
        }
        $this->entityManager->flush();
        return true;
    }
    
    /**
     * @param Token $token
     * @throws ORMException
     * @return bool
     */
    public function delete(Token $token): ?bool
    {
        try {
            $this->entityManager->remove($token);
        } catch (ORMException $e) {
            $this->logError($e->getMessage());
            return false;
        }
        $this->entityManager->flush();
        return true;
    }
}
