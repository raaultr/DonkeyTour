<?php

namespace App\Form;

use App\Entity\Donkey;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('basePrice')
            ->add('description')
            ->add('duration')
            ->add('maxAphor')
            ->add('leenguage')
            ->add('deletedAt')
            ->add('updatedAt')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('donkey', EntityType::class, [
                'class' => Donkey::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
