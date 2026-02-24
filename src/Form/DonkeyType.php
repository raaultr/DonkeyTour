<?php

namespace App\Form;

use App\Entity\Donkey;
use App\Entity\DonkeyReserve;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DonkeyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('years')
            ->add('race')
            ->add('kilogram')
            ->add('disponible')
            ->add('maxWeightr')
            ->add('photoUrl')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donkey::class,
        ]);
    }
}
