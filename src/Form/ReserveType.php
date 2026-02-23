<?php

namespace App\Form;

use App\Entity\ClientReserve;
use App\Entity\DonkeyReserve;
use App\Entity\Employee;
use App\Entity\Pay;
use App\Entity\Reserve;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReserveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reserveDate')
            ->add('state')
            ->add('details')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt')
            ->add('deletedAt')
            ->add('donkeyReserve', EntityType::class, [
                'class' => DonkeyReserve::class,
                'choice_label' => 'id',
            ])
            ->add('clientReserve', EntityType::class, [
                'class' => ClientReserve::class,
                'choice_label' => 'id',
            ])
            ->add('pay', EntityType::class, [
                'class' => Pay::class,
                'choice_label' => 'id',
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'id',
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reserve::class,
        ]);
    }
}
