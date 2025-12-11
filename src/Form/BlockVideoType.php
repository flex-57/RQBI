<?php

namespace App\Form;

use App\Entity\BlockVideo;
use App\Entity\Enums\VideoFormatEnum;
use App\Form\Traits\BlockIsActiveTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockVideoType extends AbstractType
{
    use BlockIsActiveTypeTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', TextType::class, [
                'label' => 'URL de la vidéo',
                'required' => true,
            ])
            ->add('format', EnumType::class, [
                'label' => 'Format',
                'class' => VideoFormatEnum::class,
                'choice_label' => fn(VideoFormatEnum $format) => $format->value,
            ])
            ->add('isAutoplay', CheckboxType::class, [
                'label' => 'Lecture automatique',
                'required' => false,
            ]);

        $this->addIsActiveField($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlockVideo::class,
            'csrf_protection' => true,
            'csrf_field_name' => 'block_video_token',
            'csrf_token_id'   => 'block_video_csrf',
        ]);
    }
}
