<?php

namespace App\Form;

use App\Entity\Profile;
use App\Entity\User;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture', FileType::class, [
                'label' => 'Carica la tua immagine (PNG, JPG)',
                'mapped' => false, // questo campo non è mappato direttamente nell'entità
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k', // Dimensione massima di 1MB
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Si prega di caricare un file immagine valido (JPG o PNG)',
                    ])
                ],
            ])
            ->add('description', TextareaType::class)
            ->add('dateBirth', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('coverPicture', FileType::class, [
                'label' => 'Carica la tua immagine di copertina (PNG, JPG)',
                'mapped' => false, // questo campo non è mappato direttamente nell'entità
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k', // Dimensione massima di 1MB
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Si prega di caricare un file immagine valido (JPG o PNG)',
                    ])
                ],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'firstname',
            ])
            ->add('salva', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}