<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', Emailtype::class, [
                'disabled' => true,
                'label' => "Mon adresse email"
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true,
                'label' => "Prénom"
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true,
                'label' => "Nom"
            ])

            ->add('old_password', PasswordType::class, [
                'label' => 'Mon mot de passe actuel',
                'mapped'=> false,
                'attr' => [
                    'placeholder' => 'Mon mot de passe'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped'=> false,
                'invalid_message' => ' Le mdp est incorrect',

                'label' => 'mon new mdp',
                'required' => true,
                'first_options' => [
                    'label'  => 'mon new mot de passe',
                    'attr'=>[
                        'placeholder' => 'Votre mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'confirmez  newvotre mot de passe',
                    'attr'=>[
                        'placeholder' => ' Votre new mot de passe'
                    ]
                ],


            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Submit'
            ])
         ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
