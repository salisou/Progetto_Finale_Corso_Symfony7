<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


// php bin/console doctrine:database:drop --env=test --force
// php bin/console doctrine:database:create --env=test
// php bin/console doctrine:migrations:migrate --env=test
// php bin/console doctrine:fixtures:load --env=test
// php bin/phpunit --env=test


class AuthTest extends WebTestCase
{
    public function testregistrazione(): void
    {
        $client = static::createClient();

        // Effettua una richiesta GET alla pagina di registrazione
        $crawler = $client->request('GET', '/register');

        // Invia il form di registrazione con i dati necessari
        $client->submitForm('register[Salva]', [
            'register[email]' => 'prova@gmail.com',
            'register[plainPassword][first]' => 'Test@1234',
            'register[plainPassword][second]' => 'Test@1234',
            'register[firstname]' => 'test1',
            'register[lastname]' => 'prova1',
            'register[profile]' => 101
        ]);

        // Verifica del redirect alla pagina di login dopo la registrazione
        $this->assertResponseRedirects('/login');

        // Segui il redirect alla pagina di login
        $crawler = $client->followRedirect();

        // Verifica che la pagina di login contenga il campo email
        $this->assertSelectorExists('input[name="_username"]');

        // Verifica dell'assenza di un messaggio di errore
        // $this->assertSelectorNotExists('.alert.alert-danger');
    }

    //_________________________________________________________________________________________________
    public function testautenticazione(): void
    {
        // Crea un client per simulare le richieste
        $client = static::createClient();

        // Effettua una richiesta GET alla pagina di login
        $crawler = $client->request('GET', '/login');

        // Stampa il contenuto della risposta per debug, se necessario
        // echo $client->getResponse()->getContent(); // Usa questo per ispezionare la pagina di login

        // Invia il form di login con le credenziali
        $client->submitForm('Accedi', [ // Cambia 'Accedi' con il nome o valore corretto del pulsante
            '_username' => 'prova@gmail.com',
            '_password' => 'Test@1234'
        ]);

        // Verifica che l'utente sia reindirizzato dopo il login
        $this->assertResponseRedirects('/account'); // Modifica questo percorso secondo la tua logica

        // Segui il redirect dopo il login
        $client->followRedirect();

        // Verifica che nella pagina successiva non ci sia il form di login (cioè l'utente è loggato)
        $this->assertSelectorNotExists('input[name="_username"]');
    }
}
