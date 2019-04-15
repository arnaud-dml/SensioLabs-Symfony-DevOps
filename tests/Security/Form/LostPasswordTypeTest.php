<?php

namespace App\Tests\Security\Form;

use App\Security\Form\LostPasswordType;
use Symfony\Component\Form\Test\TypeTestCase;

class LostPasswordTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'login' => 'johndoe',
        ];

        $form = $this->factory->create(LostPasswordType::class);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;
        foreach (\array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
