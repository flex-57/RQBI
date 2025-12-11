<?php

namespace App\Form\Traits;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

trait BlockIsActiveTypeTrait
{
    public function addIsActiveField(FormBuilderInterface $builder): void
    {
        $builder->add('isActive', CheckboxType::class, [
            'label' => 'Actif',
            'required' => false,
            'data' => true,
        ]);
    }
}
