<?php

namespace App\Tests\Gardener;

use App\Entity\Gardener;
use App\Gardener\GardenerType;
use Symfony\Component\Form\Test\TypeTestCase;

class GardenerTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'username' => 'John Doe',
            'email' => 'john-doe@gmail.com',
            'plainPassword' => 'johndoe',
            'roles' => ['ROLE_USER'],
        ];

        $gardener = new Gardener();
        $form = $this->factory->create(GardenerType::class, $gardener);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(
            (new Gardener())
                ->setUsername($formData['username'])
                ->setEmail($formData['email'])
                ->setRoles($formData['roles']), // Not 'plainPassword' because it's encoded in 'password' after submit
            $gardener
        );

        $view = $form->createView();
        $children = $view->children;
        foreach (\array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
