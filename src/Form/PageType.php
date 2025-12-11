<?php

namespace App\Form;

use App\Entity\Page;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('slug', TextType::class, [
                'required' => false,
                'help' => 'Laissez vide pour générer automatiquement depuis le titre',
                'attr' => ['placeholder' => 'auto-généré'],
            ])
            ->add('isPublished', CheckboxType::class, [
                'required' => false,
            ])
            ->add('isInMainNav', CheckboxType::class, [
                'required' => false,
            ])
            ->add('parent', EntityType::class, [
                'class' => Page::class,
                'choice_label' => 'title',
                'placeholder' => 'Page racine (aucun parent)',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'csrf_protection' => true,
        ]);
    }
}
