<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Employee;
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
            ->add('nombre', TextType::class, ['label' => 'Nombre Completo'])
            ->add('nif', TextType::class, ['label' => 'NIF/DNI'])
            ->add('email', EmailType::class, ['label' => 'Correo Electrónico'])
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'always_empty' => false,
            ])
            ->add('telefono', TelType::class, [
                'label' => 'Teléfono',
                'required' => false,
            ]);

        $user = $options['data'] ?? null;

        if ($user instanceof Employee) {
            $builder->add('social_security', TextType::class, [
                'label' => 'Nº Seguridad Social',
                'required' => true,
                'attr' => ['placeholder' => 'Obligatorio para empleados']
            ]);
        }

        $builder->add('contratoFirmado', CheckboxType::class, [
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