<?php

namespace App\Form;

use App\Entity\{BlockText, BlockImage, BlockSlider, BlockVideo};
use App\Entity\Enums\BlockTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockDynamicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type de bloc',
                'choices' => BlockTypeEnum::cases(),
                'choice_label' => fn(BlockTypeEnum $type) => ucfirst($type->value),
                'choice_value' => static fn(?BlockTypeEnum $type) => $type?->value,
                'mapped' => false,
                'attr' => ['data-action' => 'change->form#updateBlockForm'],
            ])
            ->add('block', BlockTextType::class, [
                'label' => false,
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    public function onPreSubmit(FormEvent $event): void
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (!isset($data['type'])) {
            return;
        }

        $type = BlockTypeEnum::from($data['type']);

        // On remplace le sous-formulaire avec la bonne entité vide
        $form->remove('block');

        $block = match ($type) {
            BlockTypeEnum::TEXT => new BlockText(),
            BlockTypeEnum::IMAGE => new BlockImage(),
            BlockTypeEnum::VIDEO => new BlockVideo(),
            BlockTypeEnum::SLIDER => new BlockSlider(),
        };

        $blockTypeClass = match ($type) {
            BlockTypeEnum::TEXT => BlockTextType::class,
            BlockTypeEnum::IMAGE => BlockImageType::class,
            BlockTypeEnum::VIDEO => BlockVideoType::class,
            BlockTypeEnum::SLIDER => BlockSliderType::class,
        };

        $form->add('block', $blockTypeClass, [
            'label' => false,
            'data' => $block, // On ajoute la bonne entité
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
        ]);
    }
}
