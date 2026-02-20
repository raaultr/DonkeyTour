<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre Completo',
                'attr' => [
                    'placeholder' => 'Ej. Juan Pérez',
                    'autocomplete' => 'name',
                ],
                'constraints' => [
                    new NotBlank(message: 'Por favor, introduce tu nombre'),
                    new Length(
                        min: 2,
                        max: 50,
                        minMessage: 'El nombre debe tener al menos {{ limit }} caracteres',
                        maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres',
                    ),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo Electrónico',
                'attr' => [
                    'placeholder' => 'ejemplo@correo.com',
                    'autocomplete' => 'email',
                ],
            ])
            ->add('nif', TextType::class, [
                'label' => 'NIF / DNI',
                'attr' => [
                    'placeholder' => '12345678X',
                ],
                'constraints' => [
                    new NotBlank(message: 'Por favor, introduce tu NIF/DNI'),
                    new Regex(
                        pattern: '/^[0-9]{8}[A-Za-z]$/',
                        message: 'El formato del NIF/DNI no es válido (ej: 12345678A)',
                    ),
                ],
            ])
            ->add('telefono', TelType::class, [
                'label' => 'Teléfono',
                'attr' => [
                    'placeholder' => '600 000 000',
                    'autocomplete' => 'tel',
                ],
                'constraints' => [
                    new NotBlank(message: 'Por favor, introduce tu teléfono'),
                    new Regex(
                        pattern: '/^[0-9\s\+\-]{9,15}$/',
                        message: 'El formato del teléfono no es válido',
                    ),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Contraseña',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Mínimo 6 caracteres',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Por favor, introduce una contraseña',
                    ),
                    new Length(
                        min: 6,
                        minMessage: 'Tu contraseña debe tener al menos {{ limit }} caracteres',
                        max: 4096,
                    ),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Acepto los términos y condiciones',
                'constraints' => [
                    new IsTrue(
                        message: 'Debes aceptar los términos y condiciones.',
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
