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
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', textType::class, [
                'label' => 'votre prénom',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom'
                ]
            ])
            ->add('lastname', textType::class, [
                'label' => 'votre nom',

                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'votre email',

                'attr' => [

                'placeholder' => 'Saisssez votre email.']
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => ' Le mdp est incorrect',

                'label' => 'votre mot de passe',
                'required' => true,
                'first_options' => [
                    'label'  => 'mot de passe',
                    'attr'=>[
                        'placeholder' => 'Votre mot de passe'
                        ]
                ],
                'second_options' => [
                    'label' => 'confirmez votre mot de passe',
                    'attr'=>[
                    'placeholder' => 'Votre mot de passe'
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
