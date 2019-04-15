<?php

namespace App\Tests\Common\Entity;

use App\Common\Entity\EntityUserTrait;
use PHPUnit\Framework\TestCase;

class EntityUserTraitTest extends TestCase
{
    use EntityUserTrait;

    public function testHasUsername()
    {
        $username = 'John Doe';
        $this->setUsername($username);
        self::assertEquals($username, $this->getUsername());
    }

    public function testHasEmail()
    {
        $email = 'john-doe@gmail.com';
        $this->setEmail($email);
        self::assertEquals($email, $this->getEmail());
    }

    public function testHasPlainPassword()
    {
        $plainPassword = 'Ghk2t73&L_Xa@Vf9';
        $this->setPlainPassword($plainPassword);
        self::assertEquals($plainPassword, $this->getPlainPassword());
    }

    public function testHasPassword()
    {
        $password = 'Ghk2t73&L_Xa@Vf9';
        $this->setPassword($password);
        self::assertEquals($password, $this->getPassword());
    }

    public function testHasRoles()
    {
        $roles = ['ROLE_ADMIN'];
        $this->setRoles($roles);
        self::assertEquals($roles, $this->getRoles());
    }

    public function testHasAddRole()
    {
        $roleDefault = 'ROLE_USER';
        $role = 'ROLE_ADMIN';
        self::assertEquals([$roleDefault], $this->getRoles());
        $this->addRole($role);
        self::assertEquals([$roleDefault, $role], $this->getRoles());
    }

    public function testHasRemoveRole()
    {
        $roleDefault = 'ROLE_USER';
        $role = 'ROLE_ADMIN';
        $this->addRole($role);
        $this->removeRole($role);
        self::assertEquals([$roleDefault], $this->getRoles());
    }

    public function testHasSalt()
    {
        self::assertNull($this->getSalt());
    }

    public function testHasSerialize()
    {
        $serialize = $this->serialize();
        self::assertEquals($this, $this->unserialize($serialize));
    }
}
