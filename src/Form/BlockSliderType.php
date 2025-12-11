<?php

namespace App\Form;

use App\Entity\BlockSlider;
use App\Form\Traits\BlockIsActiveTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockSliderType extends AbstractType
{
    use BlockIsActiveTypeTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('images', CollectionType::class, [
                'entry_type' => null,
                'label' => 'Images du slider (URLs)',
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlockSlider::class,
        ]);
    }
}
