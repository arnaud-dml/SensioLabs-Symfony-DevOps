<?php

namespace App\Tests\Gardener;

use App\Entity\Gardener;
use App\Gardener\GardenerManager;
use Doctrine\Common\Persistence\ObjectManager;
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

    public function testCreate()
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

    public function testCreateWithEmptyData()
    {
        $this->expectException(MissingOptionsException::class);
        $this->manager->createFromArray([]);
    }
}
