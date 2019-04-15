<?php

namespace App\Tests\Security\Form;

use App\Entity\Gardener;
use App\Security\Form\RegisterType;
use Symfony\Component\Form\Test\TypeTestCase;

class RegisterTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'username' => 'John Doe',
            'email' => 'john-doe@gmail.com',
            'plainPassword' => 'johndoe',
        ];

        $gardener = new Gardener();
        $form = $this->factory->create(RegisterType::class, $gardener);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(
            (new Gardener())
                ->setUsername($formData['username'])
                ->setEmail($formData['email']), // Not 'plainPassword' because it's encoded in 'password' after submit
            $gardener
        );

        $view = $form->createView();
        $children = $view->children;
        foreach (\array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
