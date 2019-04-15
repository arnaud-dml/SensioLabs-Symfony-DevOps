<?php

namespace App\Tests\Gardener;

use App\Entity\Gardener;
use App\Entity\Token;
use App\Manager\TokenManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class TokenManagerTest extends TestCase
{
    /**
     * @var MockObject|ObjectManager
     */
    private $entityManager;

    /**
     * @var MockObject|TokenGeneratorInterface
     */
    protected $tokenGenerator;

    /**
     * @var GardenerManager
     */
    private $manager;

    protected function setUp()
    {
        $this->entityManager = $this->createMock(ObjectManager::class);
        $this->tokenGenerator = $this->createMock(TokenGeneratorInterface::class);
        $this->manager = new TokenManager($this->entityManager, $this->tokenGenerator);
    }

    public function testCreateFromArray()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('persist');

        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        $this->tokenGenerator
            ->expects(self::once())
            ->method('generateToken')
            ->willReturn('OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc');

        $token = $this->manager->createFromArray([
            'gardener' => new Gardener(),
            'type' => 'REGISTER',
            'expired_at' => 'now +1 day',
        ]);
        self::assertInstanceOf(Token::class, $token);
    }

    public function testCreateFromArrayException()
    {
        $this->expectException(MissingOptionsException::class);
        $this->manager->createFromArray([]);
    }

    public function testCreateRegisterToken()
    {
        $this->tokenGenerator
            ->expects(self::once())
            ->method('generateToken')
            ->willReturn('OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc');

        self::assertInstanceOf(Token::class, $this->manager->createRegisterToken(new Gardener()));
    }

    public function testCreateLostPasswordToken()
    {
        $this->tokenGenerator
            ->expects(self::once())
            ->method('generateToken')
            ->willReturn('OEYbbWWkRd6eCncHmboBhHQBJqRCsET-kzmp4LJYHPc');

        self::assertInstanceOf(Token::class, $this->manager->createLostPasswordToken(new Gardener()));
    }

    public function testSave()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        self::assertTrue($this->manager->save(new Token()));
    }

    public function testSaveException()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('persist')
            ->willThrowException(new ORMException());

        self::assertFalse($this->manager->save(new Token()));
    }

    public function testDelete()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        self::assertTrue($this->manager->delete(new Token()));
    }

    public function testDeleteException()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('remove')
            ->willThrowException(new ORMException());

        self::assertFalse($this->manager->delete(new Token()));
    }
}
