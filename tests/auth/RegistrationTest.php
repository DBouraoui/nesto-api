<?php

namespace App\Tests\auth;

use App\Tests\DatabaseTransactionTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationTest extends WebTestCase
{
    use DatabaseTransactionTrait;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->startTransaction();

        $this->client->setServerParameters([
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ]);
    }

    public function testSuccessfulRegistration(): void
    {
        $userData = [
            'email' => 'nouvel.agent@agence.com',
            'password' => 'MotDePasseTRESSECURISE123!',
            'firstName' => 'Jean',
            'lastName' => 'Dupont',
            'type' => "agency"
        ];

        $this->client->request(
            'POST',
            '/api/auth',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($userData)
        );

        // On vérifie que le statut HTTP est 201 (Created)
        $this->assertResponseStatusCodeSame(201);

        // On vérifie que le Content-Type est bien JSON
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        // On peut décoder la réponse (si elle retourne le nouvel utilisateur ou un message)
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        // On vérifie qu'un champ attendu est présent (ex: l'ID de l'utilisateur créé)
        $this->assertTrue($responseContent['success']);
        $this->assertEquals('user created', $responseContent['message']);

    }

    public function testInvalidEmailRegistration(): void
    {
        $userData = [
            'email' => 'email_invalide',
            'password' => 'SecurePass123!',
            'firstName' => 'Test',
            'lastName' => 'Fail',
            'type' => 'agency'
        ];

        // 2. Simuler la requête POST
        $this->client->request(
            'POST',
            '/api/auth',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($userData)
        );

        // 3. Vérifications (Assertions)

        // On vérifie que le statut HTTP indique un problème client (400 ou 422)
        $this->assertResponseStatusCodeSame(422);

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertStringContainsString(
            'The provided value is not a valid email address.',
            $responseContent['detail']
        );
    }

    public function testInvalideLengthPasswordRegistration(): void
    {
        $userData = [
            'email' => 'email@gmail.com',
            'password' => '123',
            'firstName' => 'Test',
            'lastName' => 'Fail',
            'type' => 'agency'
        ];

        // 2. Simuler la requête POST
        $this->client->request(
            'POST',
            '/api/auth',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($userData)
        );

        // On vérifie que le statut HTTP indique un problème client (422 ou 422)
        $this->assertResponseStatusCodeSame(422);

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertStringContainsString(
            'password: The password must be at least 8 characters long.',
            $responseContent['detail']
        );
    }

    public function testInvalidTypeRegistration(): void
    {
        $userData = [
            'email' => 'email@gmail.com',
            'password' => 'SecurePass123!',
            'firstName' => 'Test',
            'lastName' => 'Fail',
            'type' => 'nomad'
        ];

        // 2. Simuler la requête POST
        $this->client->request(
            'POST',
            '/api/auth',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($userData)
        );

        // 3. Vérifications (Assertions)

        // On vérifie que le statut HTTP indique un problème client (422 ou 422)
        $this->assertResponseStatusCodeSame(422);
    }

    protected function tearDown(): void
    {
        $this->rollbackTransaction();
        $this->client = null;

        static::ensureKernelShutdown();
        parent::tearDown();
    }
}
