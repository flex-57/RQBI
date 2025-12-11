<?php

namespace App\Form;

use App\Entity\BlockImage;
use App\Form\Traits\BlockIsActiveTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockImageType extends AbstractType
{
    use BlockIsActiveTypeTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', TextType::class, [
                'label' => 'URL de l’image',
                'required' => true,
            ])
            ->add('alt', TextType::class, [
                'label' => 'Texte alternatif',
                'required' => false,
            ])
            ->add('caption', TextType::class, [
                'label' => 'Légende',
                'required' => false,
            ]);

        $this->addIsActiveField($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlockImage::class,
            'csrf_protection' => true,
            'csrf_field_name' => 'block_image_token',
            'csrf_token_id'   => 'block_image_csrf',
        ]);
    }
}
