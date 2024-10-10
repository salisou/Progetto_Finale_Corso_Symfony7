<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titolo dell\'articolo',
                'constraints' => new Length(min: 10),
                'attr' => [
                    'placeholder' => 'Inserisci il titolo dell\'articolo',
                ]
            ])
            ->add('content',  TextareaType::class, [
                'label' => 'Contenuto',
                'attr' => [
                    'placeholder' => 'Inserisci il contenuto dell\'articolo'
                ]
            ])
            ->add('imageUrl')

            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'lastname',
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
            ]);
        // Aggiungi 'createdAt' solo se non è una modifica
        if (!$options['is_edit']) {
            $builder->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Data di creazione',
                'attr' => [
                    'readonly' => true
                ]
            ]);
        }
        // Aggiungi 'updatedAt' solo se è una modifica
        if ($options['is_edit']) {
            $builder->add('updatedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Data di aggiornamento',
                'attr' => [
                    'readonly' => true
                ]
            ]);
        }

        $builder->add(
            'Slava',
            SubmitType::class,
            [
                'label' => $options['submit_label'],
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'submit_label' => 'Salva', // Valore di default per l'etichetta del bottone
            'is_edit' => false, // Di default, si presume che sia una creazione
        ]);
    }
}
