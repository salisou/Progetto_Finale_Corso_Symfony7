<?php

namespace App\Form;

use App\Entity\Profile;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'placeholder' => 'Inserisci il tuo indirizzo e-mail'
                ]
            ])
            // ])->add('roles')
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Password',
                    'attr' => [
                        'placeholder' => 'Inserisci la password'
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Conferma Password',
                    'attr' => [
                        'placeholder' => 'Conferma la password'
                    ],
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La Password non puo essere vuota']),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'La password deve avere almeno {{ limit }} caratteri',
                        'max' => 30,
                        'maxMessage' => 'La password non deve avere più di {{ limit }} caratteri',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/',
                        'message' => 'La password deve avere almeno una lettera maiuscola, una
                        minuscola, un numero e un carattere speciale',
                    ])
                ],
                'mapped' => false
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Nome',
                'attr' => [
                    'placeholder' => 'Inserisci il tuo nome'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Il nome non puo essere vuoto']),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Il nome deve avere almeno {{ limit }} caratteri',
                        'max' => 20,
                        'maxMessage' => 'Il nome non deve avere più di {{ limit }} caratteri',
                    ])
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Cognome',
                'attr' => [
                    'placeholder' => 'Inserisci il tuo cognome'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Il nome non puo essere vuoto']),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Il nome deve avere almeno {{ limit }} caratteri',
                        'max' => 20,
                        'maxMessage' => 'Il nome non deve avere più di {{ limit }} caratteri',
                    ])
                ]
            ])

            ->add('profile', EntityType::class, [
                'class' => Profile::class,
                'choice_label' => function (Profile $profile) {
                    // Restituisci un campo leggibile dell'oggetto `User` associato al `Profile`
                    return $profile->getUser()->getFirstname() . ' ' . $profile->getUser()->getLastname();
                },
                'placeholder' => 'Seleziona un profilo',  // Aggiungi questa riga
                'attr' => [
                    'class' => 'form-control',  // Aggiungi classi CSS se necessario
                    'disabled' => false,  // Mantieni il campo abilitato
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Il profilo non puo essere vuoto']),
                ]
            ])
            ->add('Salva', SubmitType::class, [
                'label' => 'Salva',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}