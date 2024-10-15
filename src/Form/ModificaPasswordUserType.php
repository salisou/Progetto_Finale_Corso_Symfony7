<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ModificaPasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                'label' => 'Password attuale',
                'attr' => [
                    'placeholder' => 'Inserisci la tua password attuale',
                ],
                'mapped' => false  // Campo non mappato direttamente alla proprietà dell'entità utente
            ])
            // Campo per la nuova password e conferma (RepeatedType gestisce l'inserimento di due campi uguali)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,  // Il tipo di input è una password
                'constraints' => [
                    new Length([
                        // Imposta una lunghezza minima e massima per la password
                        'min' => 4,
                        'max' => 30
                    ])
                ],
                'first_options'  => [
                    'label' => 'Nuova Password', // Etichetta per il primo campo password
                    'hash_property_path' => 'password', // (Opzionale) può essere usato per specificare la proprietà da mappare per l'hashing della password
                    'attr' => [
                        'placeholder' => 'Inserisci la tua nuova password',  // Placeholder del primo campo password
                    ]
                ],
                'second_options' => [
                    'label' => 'Conferma Nuova Password',  // Etichetta per il secondo campo di conferma password
                    'attr' => [
                        'placeholder' => 'Conferma la tua nuova password', // Placeholder del secondo campo password
                    ]
                ],
                'mapped' => false // Campo non mappato direttamente alla proprietà dell'entità utente

            ])

            ->add(
                'salva',
                SubmitType::class,
                [
                    'label' => 'Aggiorna la Password',
                    'attr' => [
                        'class' => 'btn btn-success'
                    ]
                ]
            )

            // Listener per l'evento di submit del form
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                // dd('Ok il mio evento funziona!');

                // Recupera il form attuale
                $form = $event->getForm();

                // Recupera l'utente attuale e il password hasher dal form
                $user = $form->getConfig()->getOptions()['data'];
                $passowrdHasher = $form->getConfig()->getOptions()['userPasswordHasher'];

                //Recupera le password inserita dall'utente e confronta a quella del db
                $isValid = $passowrdHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()  // Recupera la password attuale inserita nel form
                );

                // Verifica se la password attuale inserita dall'utente corrisponde a quella salvata nel database
                if (!$isValid) {
                    $form->get('actualPassword')->addError(
                        new FormError(
                            'La password attuale non è corretta'
                        )
                    );
                }
            });
    }

    //Configura le opzioni predefinite per il form, inclusa l'entità 'User' e il password hasher.
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Imposta i valori predefiniti per il form
        $resolver->setDefaults([
            'data_class' => User::class,  // Imposta l'entità User come classe a cui il form è collegato
            'userPasswordHasher' => false  // Disabilita il password hasher di default, sarà passato come opzione manualmente
        ]);
    }
}