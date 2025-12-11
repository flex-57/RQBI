<?php

namespace App\Form;

use App\Entity\BlockText;
use App\Form\Traits\BlockIsActiveTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockTextType extends AbstractType
{
    use BlockIsActiveTypeTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Contenu texte',
                'required' => true,
            ]);

        $this->addIsActiveField($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlockText::class,
            'csrf_protection' => true,
            'csrf_field_name' => 'block_text_token',
            'csrf_token_id'   => 'block_text_csrf',
        ]);
    }
}
