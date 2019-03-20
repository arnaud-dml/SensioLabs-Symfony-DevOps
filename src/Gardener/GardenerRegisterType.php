<?php

namespace App\Gardener;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GardenerRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('roles')
        ;
    }

    public function getParent()
    {
        return GardenerType::class;
    }
}
