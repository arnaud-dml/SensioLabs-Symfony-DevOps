<?php

namespace App\Tests\Security\Form;

use App\Security\Form\ResetPasswordType;
use Symfony\Component\Form\Test\TypeTestCase;

class ResetPasswordTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'plainPassword' => 'johndoe',
        ];

        $form = $this->factory->create(ResetPasswordType::class);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;
        foreach (\array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
