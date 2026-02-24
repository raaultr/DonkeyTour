<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Campos de identificación
            ->add('nombre', TextType::class, [
                'label' => 'Nombre Completo',
            ])
            ->add('nif', TextType::class, [
                'label' => 'NIF/DNI',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo Electrónico',
            ])
            
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'always_empty' => false,
            ])

            ->add('telefono', TelType::class, [
                'label' => 'Teléfono',
                'required' => false,
            ])

            ->add('contratoFirmado', CheckboxType::class, [
                'label' => '¿Contrato firmado?',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
