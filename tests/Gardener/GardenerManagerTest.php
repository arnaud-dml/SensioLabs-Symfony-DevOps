<?php

namespace App\Tests\Gardener;

use App\Entity\Gardener;
use App\Gardener\GardenerManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GardenerManagerTest extends TestCase
{
    /**
     * @var MockObject|ObjectManager
     */
    private $entityManager;

    /**
     * @var MockObject|UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * @var GardenerManager
     */
    private $manager;

    protected function setUp()
    {
        $this->entityManager = $this->createMock(ObjectManager::class);
        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->manager = new GardenerManager($this->entityManager, $this->passwordEncoder);
    }

    public function testCreateFromArray()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('persist');

        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        $this->passwordEncoder
            ->expects(self::once())
            ->method('encodePassword')
            ->willReturn('$2y$13$yFTtt40t7456iSPY7bx9euJmz924Um15WRIo6Fg6T8je5cdJYkad.');

        $gardener = $this->manager->createFromArray([
            'username' => 'John Doe',
            'email' => 'john-doe@gmail.com',
            'plainPassword' => 'johndoe',
        ]);

        self::assertInstanceOf(Gardener::class, $gardener);
    }

    public function testCreateFromArrayException()
    {
        $this->expectException(MissingOptionsException::class);
        $this->manager->createFromArray([]);
    }

    public function testEncodePassword()
    {
        $gardener = new Gardener();
        $gardener->setPlainPassword('johndoe');

        $this->passwordEncoder
            ->expects(self::once())
            ->method('encodePassword')
            ->willReturn('$2y$13$yFTtt40t7456iSPY7bx9euJmz924Um15WRIo6Fg6T8je5cdJYkad.');

        self::assertInstanceOf(Gardener::class, $this->manager->encodePassword($gardener));
    }

    public function testEncodePasswordException()
    {
        $this->expectException(MissingOptionsException::class);
        $this->manager->encodePassword(new Gardener());
    }

    public function testLogin()
    {
        $gardener = new Gardener();
        self::assertEquals(0, $gardener->getFailures());
        $gardener->incFailures();
        self::assertEquals(1, $gardener->getFailures());
        $gardener->incFailures();
        self::assertEquals(2, $gardener->getFailures());

        $this->manager->login($gardener, false);
        self::assertNull($gardener->getFailures());
        self::assertTrue($gardener->isLocked());

        $this->manager->login($gardener, true);
        self::assertNull($gardener->getFailures());
        self::assertFalse($gardener->isLocked());
    }

    public function testRegister()
    {
        $gardener = new Gardener();
        $gardener->setUsername('John Doe');
        $gardener->setEmail('john-doe@gmail.com');
        $gardener->setPlainPassword('johndoe');

        $this->passwordEncoder
            ->expects(self::once())
            ->method('encodePassword')
            ->willReturn('$2y$13$yFTtt40t7456iSPY7bx9euJmz924Um15WRIo6Fg6T8je5cdJYkad.');

        self::assertInstanceOf(Gardener::class, $this->manager->register($gardener));
    }

    public function testSave()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        self::assertTrue($this->manager->save(new Gardener()));
    }

    public function testSaveException()
    {
        $this->entityManager
            ->expects(self::once())
            ->method('persist')
            ->willThrowException(new ORMException());

        self::assertFalse($this->manager->save(new Gardener()));
    }
}
